<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalDay extends Model
{
    protected $table = 'total_day';

    public $timestamps = false;

    protected $fillable = ['date', 'total_items', 'total_amount'];
}
