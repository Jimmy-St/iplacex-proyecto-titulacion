<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Ticket;
use App\Models\Picker;
use App\Models\PickingTask;
use App\Models\PickingAssignment;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Throwable;

use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    public function index()
    {
        $fecha  = request('fecha', date('Y-m-d'));
        $buscar = request('buscar');
        $estado = request('estado');

        $tickets = Ticket::with('pickingTask')
            ->whereDate('created_at', $fecha)
            ->when($buscar, function ($q) use ($buscar) {
                $q->where(function ($query) use ($buscar) {
                    $query->where('ticket_number', 'LIKE', "%{$buscar}%")
                        ->orWhere('customer', 'LIKE', "%{$buscar}%")
                        ->orWhere('seller', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($estado, function ($q) use ($estado) {
                $q->whereHas('pickingTask', fn($query) => $query->where('status', $estado));
            })
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets', 'fecha', 'estado'));
    }

    public function show($ticket_number)
    {
        // 1. Buscamos el ticket cargando sus items, la tarea de picking y los pickers asociados a esa tarea
        $ticket = Ticket::with(['items', 'pickingTask.pickers'])
            ->where('ticket_number', $ticket_number)
            ->first();

        // 2. Traemos todos los pickers disponibles con el conteo de sus tareas activas en curso
        $pickers = Picker::withCount(['pickingTasks as active_tasks_count' => function ($query) {
            $query->whereIn('status', ['PENDIENTE', 'PREPARANDO']);
        }])
            ->orderBy('first_name', 'asc')
            ->get();

        return view('tickets.show', compact('ticket', 'pickers'));
    }

    // actualiza Pickers en Ticket
    public function updatePickers(Request $request)
    {
        // 1. Recibimos los datos del JSON
        $ticketId = $request->input('ticket_id');
        $pickerIds = $request->input('pickers', []); // Ej: [1, 2, 3] o []

        // 2. Calculamos el estado según la cantidad de pickers
        $status = empty($pickerIds) ? 'PENDIENTE' : 'PREPARANDO';

        // 3. Verificamos que el ticket exista
        $ticket = Ticket::findOrFail($ticketId);

        // 4. Buscamos la tarea asociada o la creamos al vuelo si no existe (para tickets antiguos)
        $task = PickingTask::firstOrCreate(
            ['ticket_id' => $ticket->id],
            ['status' => 'PENDIENTE'] // Estado inicial por defecto si se crea nueva
        );

        // 5. Sincronizamos los pickers en la tabla pivote
        $task->pickers()->sync($pickerIds);

        // 6. Actualizamos el estado de la tarea
        $task->update(['status' => $status]);

        // 7. Retornamos la respuesta confirmando el éxito
        return response()->json([
            'success' => true,
            'message' => 'Pickers y estado de la tarea actualizados correctamente',
            'ticket_id' => $ticket->id,
            'picking_task_id' => $task->id,
            'pickers_sincronizados' => $pickerIds,
            'status_tarea' => $status
        ]);
    }
    // Endpoint Web
    public function completeTicket(Request $request)
    {
        $ticketId = $request->input('ticket_id');

        // 1. Verificamos que el ticket exista
        $ticket = Ticket::findOrFail($ticketId);

        // 2. Buscamos la tarea de picking asociada
        $task = PickingTask::where('ticket_id', $ticket->id)->firstOrFail();

        // 3. Actualizamos el estado a COMPLETADO
        $task->update(['status' => 'COMPLETADO']);

        // 4. Retornamos la respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Ticket y tarea de picking completados exitosamente',
            'ticket_id' => $ticket->id,
            'picking_task_id' => $task->id,
            'status_tarea' => 'COMPLETADO'
        ]);
    }
}
