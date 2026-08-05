<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'kindergarten_id', 'category', 'description', 'amount', 'date', 'receipt_image', 'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
