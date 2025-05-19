<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // إضافة هذه

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
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    // تم حذف علاقة projects() HasMany القديمة
    // public function projects(): HasMany { ... }

    // إضافة علاقة المشاريع المتعددة
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_specialization', 'specialization_id', 'project_id');
    }


    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }

    public function adminActions(): BelongsToMany
    {
        return $this->belongsToMany(AdminUser::class, 'admin_specialization_actions', 'specialization_id', 'admin_id')
                    ->withPivot('action_type', 'action_at', 'notes')
                    ->withTimestamps('action_at');
    }
}