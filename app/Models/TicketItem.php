<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Importante para el camino de regreso

class TicketItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'product_code',
        'product_name',
        'quantity',
        'price',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    /**
     * Relación: Cada Ítem pertenece a un Ticket único
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
