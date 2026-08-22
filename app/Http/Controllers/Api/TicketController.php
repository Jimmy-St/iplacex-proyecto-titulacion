<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use App\Http\Requests\TicketRequest;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // Para pantalla
    public function latest()
    {
        $tickets = Ticket::orderBy('id', 'desc')
            ->take(12)
            ->get();

        return response()->json([
            'message' => 'Últimos 12 tickets.',
            'count'   => $tickets->count(),
            'tickets' => $tickets,
        ], 200);
    }

    public function show($ticket_number)
    {
        return $ticket_number;
    }

    /**
     * Almacena un nuevo ticket junto con sus ítems.
     * Este es el endpoint que consumirá la extensión de Chrome.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Los datos de cabecera vienen anidados dentro de "header" en el JSON de la extensión.
            $ticket = Ticket::create([
                'ticket_number' => $request->input('header.idTicket'),
                'seller'        => $request->input('header.seller'),
                'customer'      => $request->input('header.customer'),
                'comment'       => $request->input('header.comment'),
                'payment_type'  => $request->input('header.paymentType'),
                'total_amount'  => $this->normalizeNumber($request->input('header.totalAmount')),
            ]);

            // Traducimos los items del formato del JS (code/description/unitPrice/totalPrice)
            // al formato que espera la tabla ticket_items (product_code/product_name/price/subtotal).
            $items = collect($request->input('items', []))->map(function ($item) {
                return [
                    'product_code' => $item['code'],
                    'product_name' => $item['description'],
                    'quantity'     => $item['quantity'],
                    'price'        => $this->normalizeNumber($item['unitPrice']),
                    'subtotal'     => $this->normalizeNumber($item['totalPrice']),
                ];
            })->toArray();

            $ticket->items()->createMany($items);

            // ==========================================
            // ACTUALIZACIÓN DE LA TABLA DE AGREGACIÓN TOTAL_DAY
            // ==========================================
            $today = now()->toDateString();

            // Calculamos métricas del ticket entrante (sumatoria de cantidades de ítems y monto)
            $ticketItemsCount = collect($items)->sum('quantity');
            $ticketAmount = $ticket->total_amount;

            DB::table('total_day')->updateOrInsert(
                ['date' => $today],
                [
                    'total_tickets' => DB::raw('total_tickets + 1'),
                    'total_items'   => DB::raw("total_items + {$ticketItemsCount}"),
                    'total_amount'  => DB::raw("total_amount + {$ticketAmount}"),
                ]
            );
            // ==========================================

            DB::commit();

            Log::info('Ticket creado con éxito y total_day actualizado', [
                'ticket_number' => $ticket->ticket_number,
                'seller'        => $ticket->seller,
                'total_amount'  => $ticket->total_amount,
                'items_count'   => count($items)
            ]);

            return response()->json([
                'message'   => 'Ticket y sus ítems creados con éxito.',
                'ticket_id' => $ticket->id,
            ], 201);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Error al crear Ticket', [
                'ticket_number' => $request->input('header.idTicket'),
                'error'         => $e->getMessage(),
                'linea'         => $e->getLine(),
                'trace'         => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Error al crear el ticket.',
                'error' => $e->getMessage()
            ], 500);
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

        // Proceso de actualización con Transacción
        try {
            DB::beginTransaction();

            $seller = Seller::where('employee_code', $request->input('seller_employee_code'))->firstOrFail();

            // Actualizamos la cabecera
            $ticket->update([
                'ticket_number' => $request->input('ticket_number'),
                'seller_id'     => $seller->id,
                'total_amount'  => $request->input('total_amount'),
                'issued_at'     => $request->input('issued_at'),
            ]);

            // Drop: Eliminamos los ítems viejos asociados
            $ticket->items()->delete();

            // Recreate: Insertamos los nuevos ítems en lote
            $ticket->items()->createMany($request->input('items'));

            DB::commit();

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
     * Limpia un monto en formato local (ej: "118.540", "$0") y lo convierte a número.
     * Orden: quitar puntos de miles -> coma decimal a punto -> quitar símbolo $.
     */
    private function normalizeNumber($value): float
    {
        $clean = str_replace('.', '', (string) $value);
        $clean = str_replace(',', '.', $clean);
        $clean = str_replace('$', '', $clean);
        $clean = trim($clean);

        return $clean === '' ? 0.0 : (float) $clean;
    }
}
