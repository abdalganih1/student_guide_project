<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourseEnrollment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'student_course_enrollments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'course_id',
        'semester_enrolled', // هذا الحقل ضروري ومختلف عن الطابع الزمني
        'status',
        'grade',
        'completion_date',
        'notes',
        // 'enrollment_date', // إذا كنت تستخدم useCurrent() في الهجرة، لا تضعه هنا إلا إذا كنت ستتحكم فيه يدوياً
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enrollment_date' => 'datetime', // تاريخ التسجيل (الذي يستخدم useCurrent في الهجرة)
        'completion_date' => 'date', // تاريخ الإكمال (تاريخ فقط)
        // 'attended' => 'boolean', // إذا أضفت حقل attended إلى جدول الربط هذا (كان في تسجيل الفعاليات)
    ];

    /**
     * Get the student that owns the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the course that owns the enrollment.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // إذا كنت تحتاج للوصول إلى معلومات المدير الذي قام بإجراء إداري على هذا التسجيل (غير موجود في جدولك الحالي)
    // public function adminActionBy(): BelongsTo
    // {
    //     return $this->belongsTo(AdminUser::class, 'admin_action_by_id');
    // }
}