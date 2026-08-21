<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePickerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:pickers,employee_code'],
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'display_name'  => ['nullable', 'string', 'max:50'],
            'zone_assigned' => ['nullable', 'string', 'max:50'],
            'is_active'     => ['nullable', 'boolean'],
            'status'        => ['required', 'string', 'max:40'],
        ];
    }
}
