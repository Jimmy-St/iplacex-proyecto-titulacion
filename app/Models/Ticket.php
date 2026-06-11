<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'seller_id',
        'total_amount',
        'issued_at',
    ];

    /**
     * Obtener los ítems asociados a este ticket.
     */
    public function items(): HasMany
    {
        // Le indicamos que se conecta con TicketItem usando la llave foránea 'ticket_id'
        return $this->hasMany(TicketItem::class, 'ticket_id');
    }

    /**
     * Obtener el vendedor que emitió este ticket.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
