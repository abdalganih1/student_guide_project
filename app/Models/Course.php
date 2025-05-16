<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization_id',
        'code',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'semester_display_info',
        'year_level',
        'credits',
        'is_enrollable',
        'enrollment_capacity',
        'created_by_admin_id',
        'last_updated_by_admin_id',
    ];

    protected $casts = [
        'is_enrollable' => 'boolean',
    ];

    // العلاقات
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function resources()
    {
        return $this->hasMany(CourseResource::class);
    }

    // public function instructors() // المدرسون المعينون لهذا المقرر
    // {
    //     return $this->belongsToMany(Instructor::class, 'course_instructor_assignments', 'course_id', 'instructor_id')
    //                 ->withPivot('semester_of_assignment', 'role_in_course')
    //                 ->withTimestamps();
    // }

    public function enrolledStudents() // الطلاب المسجلون في هذا المقرر
    {
        return $this->belongsToMany(Student::class, 'student_course_enrollments', 'course_id', 'student_id')
                    ->withPivot('enrollment_date', 'semester_enrolled', 'status', 'grade', 'completion_date', 'notes')
                    ->withTimestamps();
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }

    public function relatedNotifications()
    {
        return $this->hasMany(Notification::class, 'related_course_id');
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id'); // تأكد من أن المفتاح الأجنبي صحيح
    }
     public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id'); // تأكد من أن المفتاح الأجنبي صحيح
    }
    
}