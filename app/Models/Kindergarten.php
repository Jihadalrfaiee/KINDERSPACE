<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kindergarten extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'logo', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}