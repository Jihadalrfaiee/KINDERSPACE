<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = [
        'kindergarten_id', 'academic_year', 'tuition_fee', 'transportation_fee', 'installments_count', 'is_active',
    ];

    protected $casts = [
        'tuition_fee' => 'decimal:2',
        'transportation_fee' => 'decimal:2',
        'installments_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }
}
