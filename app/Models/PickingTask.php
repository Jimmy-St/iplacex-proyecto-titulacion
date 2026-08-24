<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickingTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'status',
    ];

    // Relación con el Ticket (Pertenece a un ticket)
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relación N:N con los Pickers a través de la tabla intermedia
    public function pickers()
    {
        return $this->belongsToMany(Picker::class, 'picking_assignments')
            ->withTimestamps();
    }
}
