<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Requests\TicketRequest;

class TicketsController extends Controller
{
    public function index()
    {
        $fecha  = request('fecha', date('Y-m-d'));
        $buscar = request('buscar');

        $tickets = Ticket::with(['seller', 'items'])
            ->whereDate('issued_at', $fecha)
            ->when($buscar, fn($q) => $q->where('ticket_number', 'LIKE', "%{$buscar}%"))
            ->latest()
            ->get();

        return view('tickets.index', compact('tickets', 'fecha'));
    }

    public function show($numero)
    {
        $ticket = Ticket::with('items')->where('ticket_number', $numero)->firstOrFail();

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Almacena un nuevo ticket junto con sus ítems.
     * Endpoint consumido por la extensión de Chrome.
     */
    public function store(TicketRequest $request)
    {
        try {
            DB::beginTransaction();

            $seller = Seller::where('employee_code', $request->input('seller_employee_code'))->firstOrFail();

            $ticket = Ticket::create([
                'ticket_number' => $request->input('ticket_number'),
                'seller_id'     => $seller->id,
                'total_amount'  => $request->input('total_amount'),
                'issued_at'     => $request->input('issued_at'),
            ]);

            $ticket->items()->createMany($request->input('items'));

            DB::commit();

            Log::info('Ticket creado con éxito', [
                'ticket_number' => $request->input('ticket_number'),
                'seller_code'   => $request->input('seller_employee_code'),
                'total_amount'  => $request->input('total_amount'),
                'items_count'   => count($request->input('items', []))
            ]);

            return response()->json([
                'message'   => 'Ticket y sus ítems creados con éxito.',
                'ticket_id' => $ticket->id,
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear Ticket', [
                'ticket_number' => $request->input('ticket_number'),
                'error'         => $e->getMessage(),
                'linea'         => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al crear el ticket.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualiza un ticket existente junto con sus ítems.
     * Endpoint consumido por la extensión de Chrome.
     */
    public function update(TicketRequest $request, $ticket_number)
    {
        $ticket = Ticket::where('ticket_number', $ticket_number)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado.'], 404);
        }

        try {
            DB::beginTransaction();

            $seller = Seller::where('employee_code', $request->input('seller_employee_code'))->firstOrFail();

            $ticket->update([
                'ticket_number' => $request->input('ticket_number'),
                'seller_id'     => $seller->id,
                'total_amount'  => $request->input('total_amount'),
                'issued_at'     => $request->input('issued_at'),
            ]);

            $ticket->items()->delete();
            $ticket->items()->createMany($request->input('items'));

            DB::commit();

            Log::info('Ticket editado con éxito', [
                'ticket_number' => $request->input('ticket_number'),
                'seller_code'   => $request->input('seller_employee_code'),
                'total_amount'  => $request->input('total_amount'),
                'items_count'   => count($request->input('items', []))
            ]);

            return response()->json([
                'message'   => 'Ticket e ítems actualizados con éxito.',
                'ticket_id' => $ticket->id
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Error al editar Ticket', [
                'ticket_number' => $request->input('ticket_number'),
                'error'         => $e->getMessage(),
                'linea'         => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al actualizar el ticket.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
