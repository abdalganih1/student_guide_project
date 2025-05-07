<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'name_ar',
        'name_en',
        'title',
        'email',
        'office_location',
        'bio',
        'profile_picture_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // العلاقات
    public function faculty() // الكلية التي ينتمي إليها
    {
        return $this->belongsTo(Faculty::class);
    }

    public function deanOfFaculty() // إذا كان هذا المدرس هو عميد كلية
    {
        return $this->hasOne(Faculty::class, 'dean_id');
    }

    public function supervisedProjects()
    {
        return $this->hasMany(Project::class, 'supervisor_instructor_id');
    }

    // public function courseAssignments()
    // {
    //     // لعلاقة many-to-many عبر جدول course_instructor_assignments
    //     return $this->belongsToMany(Course::class, 'course_instructor_assignments', 'instructor_id', 'course_id')
    //                 ->withPivot('semester_of_assignment', 'role_in_course')
    //                 ->withTimestamps(); // created_at, updated_at في جدول الربط
    // }
    
    // إذا كنت قد أبقيت على جدول InstructorMediaAssociations
    // public function universityMedia()
    // {
    //     return $this->belongsToMany(UniversityMedia::class, 'instructor_media_associations', 'instructor_id', 'media_id')
    //                 ->withPivot('description')
    //                 ->withTimestamps('created_at');
    // }
}