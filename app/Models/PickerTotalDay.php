<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickerTotalDay extends Model
{
    protected $table = 'picker_total_day';

    public $timestamps = false;

    // Habilita campos para permitir la operación masiva del upsert
    protected $fillable = [
        'picker_id',
        'date',
        'total_tasks',
        'total_items',
        'total_amount',
        'total_points',
    ];

    /**
     * Relación: Un registro de totales pertenece a un Picker específico.
     */
    public function picker(): BelongsTo
    {
        return $this->belongsTo(Picker::class, 'picker_id');
    }
}
