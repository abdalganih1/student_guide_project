<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // إضافة هذه

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        // تم إزالة 'specialization_id' من هنا إذا لم يعد يشير للتخصص الرئيسي الإلزامي
        'specialization_id', // تركه هنا إذا كان لا يزال يشير للتخصص الرئيسي الاختياري
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
    // تم حذف علاقة specialization() BelongsTo إذا لم يعد هناك specialization_id يشير لتخصص وحيد
    // public function specialization(): BelongsTo { ... }

    // إضافة علاقة التخصصات المتعددة
    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'project_specialization', 'project_id', 'specialization_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'supervisor_instructor_id');
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }
}