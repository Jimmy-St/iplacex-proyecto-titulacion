<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'seller_id',
        'seller',
        'customer',
        'comment',
        'payment_type',
        'total_amount',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Relación: Un Ticket tiene muchos Ítems
     */
    public function items(): HasMany
    {
        return $this->hasMany(TicketItem::class, 'ticket_id');
    }

    /**
     * Relación: Un Ticket tiene una Tarea
     */
    public function pickingTask()
    {
        return $this->hasOne(PickingTask::class);
    }
}
