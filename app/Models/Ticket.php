<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'seller_id',
        'seller',
        'total_amount',
        'status',
        'created_at',
        'updated_at',
    ];
}
