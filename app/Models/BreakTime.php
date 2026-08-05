<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    protected $table = 'break_times';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'duration_minutes',
        'after_period',
    ];
}
