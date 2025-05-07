<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model // اسم النموذج Project
{
    use HasFactory;

    protected $table = 'projects'; // اسم الجدول projects

    protected $fillable = [
        'specialization_id',
        'title_ar',
        'title_en',
        'abstract_ar',
        'abstract_en',
        'year',
        'semester',
        'student_names',
        'supervisor_instructor_id',
        'project_type',
        'keywords',
        'created_by_admin_id',
        'last_updated_by_admin_id',
    ];

    // العلاقات
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function supervisor() // المشرف
    {
        return $this->belongsTo(Instructor::class, 'supervisor_instructor_id');
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }
}