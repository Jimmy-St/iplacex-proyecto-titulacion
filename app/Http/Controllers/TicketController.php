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
        $ticket = Ticket::with(['items', 'pickingTask.pickers'])
            ->where('ticket_number', $ticket_number)
            ->first();

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
        $ticketId = $request->input('ticket_id');
        $pickerIds = $request->input('pickers', []);

        $status = empty($pickerIds) ? 'PENDIENTE' : 'PREPARANDO';

        $ticket = Ticket::findOrFail($ticketId);

        $task = PickingTask::firstOrCreate(
            ['ticket_id' => $ticket->id],
            ['status' => 'PENDIENTE']
        );

        $task->pickers()->sync($pickerIds);

        $task->update(['status' => $status]);

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

        $ticket = Ticket::findOrFail($ticketId);

        $task = PickingTask::where('ticket_id', $ticket->id)->firstOrFail();

        $task->update(['status' => 'COMPLETADO']);

        // Add totales
        $this->accumulateDailyTotals($ticket);
        $this->accumulatePickerTotals($ticket);

        return response()->json([
            'success' => true,
            'message' => 'Ticket y tarea de picking completados exitosamente',
            'ticket_id' => $ticket->id,
            'picking_task_id' => $task->id,
            'status_tarea' => 'COMPLETADO'
        ]);
    }

    /**
     * Accumulates ticket total/day.
     * 
     * @param Ticket $ticket
     * @return void
     */
    private function accumulateDailyTotals(Ticket $ticket): void
    {
        $today = now()->toDateString();
        $totalItems = $ticket->items()->sum('quantity');

        DB::table('total_day')->updateOrInsert(
            ['date' => $today],
            [
                'total_tickets' => DB::raw('total_tickets + 1'),
                'total_items'   => DB::raw("total_items + {$totalItems}"),
                'total_amount'  => DB::raw("total_amount + {$ticket->total_amount}"),
            ]
        );
    }

    /**
     * Accumulates ticket for picker.
     * 
     * @param Ticket $ticket
     * @return void
     */
    private function accumulatePickerTotals(Ticket $ticket): void
    {
        $today = now()->toDateString();
        $pickers = $ticket->pickingTask->pickers ?? collect();
        $pickerCount = $pickers->count();

        if ($pickerCount === 0) {
            return;
        }

        $totalItems = $ticket->items()->sum('quantity');
        $totalAmount = $ticket->total_amount;

        $shareTasks = 1 / $pickerCount;
        $shareItems = $totalItems / $pickerCount;
        $shareAmount = $totalAmount / $pickerCount;

        foreach ($pickers as $picker) {
            DB::table('picker_total_day')->updateOrInsert(
                [
                    'picker_id' => $picker->id,
                    'date' => $today
                ],
                [
                    'total_tasks'  => DB::raw("total_tasks + {$shareTasks}"),
                    'total_items'  => DB::raw("total_items + {$shareItems}"),
                    'total_amount' => DB::raw("total_amount + {$shareAmount}"),
                    'total_points' => DB::raw("total_points + 0"),
                ]
            );
        }
    }
}
