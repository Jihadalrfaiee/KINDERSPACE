<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['kindergarten_id', 'name', 'order', 'is_active'];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }
}