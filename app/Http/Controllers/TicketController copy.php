<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    // public function __construct()
    // {
    //     if (!session()->has('auth')) {
    //         //return redirect('/')->send();
    //     }
    // }
    /**
     * Mostrar Listado
     * 
     */
    public function index()
    {
        // Usamos all() o get() para traer absolutamente todo. 
        //latest() asegura que los más nuevos salgan primero.
        $tickets = Ticket::with(['seller', 'items'])->latest()->get();

        return response()->json([
            'message' => 'Listado completo de tickets recuperado.',
            'data'    => $tickets
        ], 200);
    }

    /**
     * Almacena un nuevo ticket junto con sus ítems.
     * Este es el endpoint que consumirá la extensión de Chrome.
     */
    public function store(TicketRequest $request)
    {
        // Usamos una transacción para garantizar la integridad de los datos.
        try {
            DB::beginTransaction();

            // Buscamos al vendedor por su código, que es más robusto que un ID.
            $seller = Seller::where('employee_code', $request->input('seller_employee_code'))->firstOrFail();

            // 3. Creamos el Ticket principal.
            $ticket = Ticket::create([
                'ticket_number' => $request->input('ticket_number'),
                'seller_id'     => $seller->id,
                'total_amount'  => $request->input('total_amount'),
                'issued_at'     => $request->input('issued_at'),
            ]);

            // Creamos todos los ítems asociados a ese ticket
            $ticket->items()->createMany($request->input('items'));

            // Si todo ha ido bien, confirmamos los cambios en la base de datos.
            DB::commit();

            // almacenar en log
            Log::info('Ticket creado con éxito', [
                'ticket_number' => $request->input('ticket_number'),
                'seller_code'   => $request->input('seller_employee_code'),
                'total_amount'  => $request->input('total_amount'),
                'items_count'   => count($request->input('items', []))
            ]);

            return response()->json([
                'message' => 'Ticket y sus ítems creados con éxito.',
                'ticket_id' => $ticket->id,
            ], 201); // 201: Created

        } catch (Throwable $e) {
            // Si algo falla, revertimos todos los cambios.
            DB::rollBack();

            // almacenar en log
            Log::error('Error al crear Ticket', [
                'ticket_number' => $request->input('ticket_number'),
                'error'         => $e->getMessage(),
                'linea'         => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            // Y devolvemos un error para que se pueda depurar.
            return response()->json([
                'message' => 'Error al crear el ticket.',
                'error' => $e->getMessage()
            ], 500); // 500: Internal Server Error
        }
    }

    /**
     * Almacena un ticket actualizado junto con sus ítems.
     * Este es el endpoint que consumirá la extensión de Chrome.
     */
    public function update(TicketRequest $request, $ticket_number)
    {
        // 1. Buscamos el ticket por su número interno
        $ticket = Ticket::where('ticket_number', $ticket_number)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado.'], 404);
        }

        // Guardamos el ID interno
        $ticketId = $ticket->id;

        // Proceso de actualización con Transacción
        try {
            DB::beginTransaction();

            $seller = Seller::where('employee_code', $request->input('seller_employee_code'))->firstOrFail();

            // Actualizamos la cabecera
            $ticket->update([
                'ticket_number' => $request->input('ticket_number'), // Por si la extensión decide corregir el número
                'seller_id'     => $seller->id,
                'total_amount'  => $request->input('total_amount'),
                'issued_at'     => $request->input('issued_at'),
            ]);

            // Drop: Eliminamos los ítems viejos asociados
            $ticket->items()->delete();

            // Recreate: Insertamos los nuevos ítems en lote
            $ticket->items()->createMany($request->input('items'));

            DB::commit();

            // almacenar en log
            Log::info('Ticket editado con éxito', [
                'ticket_number' => $request->input('ticket_number'),
                'seller_code'   => $request->input('seller_employee_code'),
                'total_amount'  => $request->input('total_amount'),
                'items_count'   => count($request->input('items', []))
            ]);

            return response()->json([
                'message' => 'Ticket e ítems actualizados con éxito (recreación por número de ticket).',
                'ticket_id' => $ticket->id
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            // almacenar en log
            Log::error('Error al editar Ticket', [
                'ticket_number' => $request->input('ticket_number'),
                'error'         => $e->getMessage(),
                'linea'         => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al actualizar el ticket.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca y muestra un ticket junto con sus ítems.
     * Este es el endpoint que consumirá la extensión de Chrome.
     */
    public function show($ticket_number)
    {
        // Buscamos el ticket por su número cargando inmediatamente sus ítems relacionados
        $ticket = Ticket::with('items')->where('ticket_number', $ticket_number)->first();

        // Si el ticket no existe, devolvemos un 404
        if (!$ticket) {
            return response()->json([
                'message' => "El ticket número $ticket_number no existe en los registros."
            ], 404);
        }

        // Retornamos el ticket con todos sus datos e ítems
        return response()->json([
            'message' => 'Ticket recuperado con éxito.',
            'data'    => $ticket
        ], 200);
    }
}
