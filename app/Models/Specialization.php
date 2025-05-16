<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'status',
        'created_by_admin_id',
        'last_updated_by_admin_id',
    ];

    protected $casts = [
        //
    ];

    // العلاقات
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function projects() // المشاريع
    {
        return $this->hasMany(Project::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }

    public function adminActions()
    {
        // لعلاقة many-to-many عبر جدول admin_specialization_actions
        // إذا كنت ستصل إلى بيانات action_type أو notes من خلال النموذج
        return $this->belongsToMany(AdminUser::class, 'admin_specialization_actions', 'specialization_id', 'admin_id')
                    ->withPivot('action_type', 'action_at', 'notes')
                    ->withTimestamps('action_at'); // إذا كان action_at هو طابع زمني للإجراء
    }
}