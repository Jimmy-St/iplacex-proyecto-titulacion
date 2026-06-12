<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Acceso abierto por ahora
    }

    public function rules(): array
    {
        return [
            // --- REGLAS DE LA CABECERA DEL TICKET ---
            'ticket_number'        => 'required|string|unique:tickets,ticket_number',
            'seller_employee_code' => 'required|string|exists:sellers,employee_code',
            'total_amount'         => 'required|numeric',
            'issued_at'            => 'required|date',

            // --- REGLAS DE LOS ÍTEMS (ARRAY ANIDADO) ---
            'items'                => 'required|array|min:1',
            'items.*.product_code' => 'required|string',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric',
            'items.*.subtotal'     => 'required|numeric',
        ];
    }
}
