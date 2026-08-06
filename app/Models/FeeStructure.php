<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'kindergarten_id', 'academic_year', 'tuition_fee', 'transportation_fee', 'installments_count', 'is_active',
        // scheduling
        'schedule_type', 'first_due_offset_days', 'interval_months', 'custom_schedule',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'transportation_fee' => 'decimal:2',
        'installments_count' => 'integer',
        'is_active' => 'boolean',
        'first_due_offset_days' => 'integer',
        'interval_months' => 'integer',
        'custom_schedule' => 'array',
    ];

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }
}
