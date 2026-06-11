<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', // La llave foránea que une al ítem con su cabecera
        'product_code',
        'product_name',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * Obtener el ticket al que pertenece este ítem.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
