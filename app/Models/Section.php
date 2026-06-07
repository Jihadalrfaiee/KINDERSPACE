<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['category_id', 'kindergarten_id', 'name', 'max_students', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}