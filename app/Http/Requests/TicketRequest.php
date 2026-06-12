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
        $rules = [
            'seller_employee_code' => 'required|string|exists:sellers,employee_code',
            'total_amount'         => 'required|numeric',
            'issued_at'            => 'required|date',

            // Reglas del array de ítems
            'items'                => 'required|array|min:1',
            'items.*.product_code' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric',
            'items.*.subtotal'     => 'required|numeric',
        ];

        // 2. CONDICIONAL SEGÚN EL VERBO HTTP
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // --- ESTAMOS ACTUALIZANDO ---
            // Rescatamos el ticket_number que viene en la URL de la ruta
            $ticketNumberParam = $this->route('ticket_number');

            // Buscamos su ID para decirle a la regla unique que lo ignore
            $ticket = Ticket::where('ticket_number', $ticketNumberParam)->first();
            $ticketId = $ticket ? $ticket->id : null;

            $rules['ticket_number'] = 'required|string|unique:tickets,ticket_number,' . $ticketId;
        } else {
            // --- ESTAMOS CREANDO (POST) ---
            $rules['ticket_number'] = 'required|string|unique:tickets,ticket_number';
        }

        return $rules;
    }
}
