<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'dean_id',
    ];

    // العلاقات
    public function dean()
    {
        return $this->belongsTo(Instructor::class, 'dean_id');
    }

    public function specializations()
    {
        return $this->hasMany(Specialization::class);
    }

    public function instructors()
    {
        return $this->hasMany(Instructor::class);
    }

    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organizing_faculty_id');
    }

    public function universityMedia()
    {
        return $this->hasMany(UniversityMedia::class);
    }
}