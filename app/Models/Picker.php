<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Picker extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pickers';

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'display_name',
        'zone_assigned',
        'is_active',
        'status',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Retornamos display_name or fallback automatic to first_name if empty.
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ?: $this->first_name,
            set: fn(?string $value) => !empty(trim($value)) ? trim($value) : null,
        );
    }

    /**
     * Nombre completo auxiliar para vistas o reportes.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
