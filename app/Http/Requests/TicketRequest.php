<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Ticket;

class TicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Puerta abierta para el MVP
    }

    public function rules(): array
    {
        // 1. REGLAS COMUNES: Lo que se exige SIEMPRE (tanto en POST como en PUT)
        // Los nombres acá son los que manda la extensión (header.*, items[].code, etc),
        // NO los nombres de columna de la base de datos. La traducción a esos nombres
        // (product_code, price, subtotal...) pasa dentro del controller, después de validar.
        $rules = [
            'header'                  => 'required|array',
            'header.seller'           => 'required|string',
            'header.totalAmount'      => 'required',

            // Reglas del array de ítems
            'items'                   => 'required|array|min:1',
            'items.*.code'            => 'required|string',
            'items.*.description'     => 'required|string',
            'items.*.quantity'        => 'required|numeric|min:1',
            'items.*.unitPrice'       => 'required',
            'items.*.totalPrice'      => 'required',
        ];

        // 2. CONDICIONAL SEGÚN EL VERBO HTTP
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // --- ESTAMOS ACTUALIZANDO ---
            // Rescatamos el ticket_number que viene en la URL de la ruta
            $ticketNumberParam = $this->route('ticket_number');

            // Buscamos su ID para decirle a la regla unique que lo ignore
            $ticket = Ticket::where('ticket_number', $ticketNumberParam)->first();
            $ticketId = $ticket ? $ticket->id : null;

            $rules['header.idTicket'] = 'required|string|unique:tickets,ticket_number,' . $ticketId;
        } else {
            // --- ESTAMOS CREANDO (POST) ---
            $rules['header.idTicket'] = 'required|string|unique:tickets,ticket_number';
        }

        return $rules;
    }
}
