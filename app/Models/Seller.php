<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;
    /**
     * Los atributos que se pueden asignar de forma masiva.
     */
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'is_active',
    ];
}
