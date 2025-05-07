<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Model
{
    use HasFactory;

    protected $table = 'course_resources'; // تحديد اسم الجدول إذا اختلف عن الاصطلاح

    protected $fillable = [
        'course_id',
        'title_ar',
        'title_en',
        'url',
        'type',
        'description',
        'semester_relevance',
        'uploaded_by_admin_id',
    ];

    // العلاقات
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function uploadedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'uploaded_by_admin_id');
    }
}