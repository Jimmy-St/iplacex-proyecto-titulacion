<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

use App\Http\Requests\TicketRequest;

class TicketController extends Controller
{
    /**
     * Almacena un nuevo ticket junto con sus ítems.
     * Este es el endpoint que consumirá la extensión de Chrome.
     */
    public function store(TicketRequest $request)
    {
        // 1. Validamos datos de la extensión en archivo externo en StoreTicketRequest.php

        // 2. Usamos una transacción para garantizar la integridad de los datos.
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

            // 4. Creamos todos los ítems asociados a ese ticket
            $ticket->items()->createMany($request->input('items'));

            // Si todo ha ido bien, confirmamos los cambios en la base de datos.
            DB::commit();

            return response()->json([
                'message' => 'Ticket y sus ítems creados con éxito.',
                'ticket_id' => $ticket->id,
            ], 201); // 201: Created

        } catch (Throwable $e) {
            // Si algo falla, revertimos todos los cambios.
            DB::rollBack();

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
        // 1. Buscamos el ticket por su número de negocio en lugar del ID
        $ticket = Ticket::where('ticket_number', $ticket_number)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado.'], 404);
        }

        // Guardamos el ID interno para usarlo en la regla de exclusión de la validación
        $ticketId = $ticket->id;

        // 2. Validamos los datos entrantes
        $validator = Validator::make($request->all(), [
            // Validamos que el ticket_number que viene en el BODY no choque con otros (ignorando su propio ID)
            'ticket_number'        => 'required|string|unique:tickets,ticket_number,' . $ticketId,
            'seller_employee_code' => 'required|string|exists:sellers,employee_code',
            'total_amount'         => 'required|numeric',
            'issued_at'            => 'required|date',
            'items'                => 'required|array|min:1',
            'items.*.product_code' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric',
            'items.*.subtotal'     => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 3. Proceso de actualización con Transacción
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

            return response()->json([
                'message' => 'Ticket e ítems actualizados con éxito (recreación por número de ticket).',
                'ticket_id' => $ticket->id
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();

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
