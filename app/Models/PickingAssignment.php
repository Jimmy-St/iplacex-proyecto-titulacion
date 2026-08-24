<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PickingAssignment extends Pivot
{
    protected $table = 'picking_assignments';

    protected $fillable = [
        'picking_task_id',
        'picker_id',
    ];
}
