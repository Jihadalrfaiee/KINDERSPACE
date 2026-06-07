<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kindergarten_id', 'section_id', 'parent_id',
        'first_name', 'father_name', 'grandfather_name', 'family_name', 'mother_name',
        'birth_date', 'siblings_count', 'birth_order',
        'registration_number', 'nationality', 'address',
        'father_marital_status', 'mother_marital_status',
        'student_national_id', 'father_national_id',
        'needs_transportation', 'needs_bathroom_care', 'previous_diseases',
        'father_phone', 'mother_phone', 'home_phone',
        'status', 'academic_year'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'needs_transportation' => 'boolean',
        'needs_bathroom_care' => 'boolean',
    ];

    protected $appends = ['full_name', 'age'];

    // العلاقات
    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    // الخصائص المحسوبة
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->father_name} {$this->grandfather_name} {$this->family_name}";
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age . ' سنة' : '-';
    }
}