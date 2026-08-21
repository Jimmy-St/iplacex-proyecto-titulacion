<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePickerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pickerId = $this->route('picker')->id ?? $this->route('picker');

        return [
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('pickers', 'employee_code')->ignore($pickerId)],
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'display_name'  => ['nullable', 'string', 'max:50'],
            'zone_assigned' => ['nullable', 'string', 'max:50'],
            'is_active'     => ['nullable', 'boolean'],
            'status'        => ['required', 'string', 'max:40'],
        ];
    }
}
