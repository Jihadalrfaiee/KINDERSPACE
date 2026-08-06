<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    protected $fillable = [
        'student_id', 'kindergarten_id', 'academic_year', 'total_tuition', 'total_transportation', 'discount_amount', 'discount_reason', 'net_amount', 'paid_amount', 'remaining_amount', 'status',
    ];

    protected $casts = [
        'total_tuition' => 'decimal:2',
        'total_transportation' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
