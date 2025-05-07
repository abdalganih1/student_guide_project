<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // مهم للمصادقة
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // للـ API authentication

class Student extends Authenticatable // تغيير اسم الكلاس
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'students'; // تحديد اسم الجدول

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_university_id',
        'full_name_ar',
        'full_name_en',
        'email',
        'password_hash', // يجب تشفيره قبل الحفظ
        'specialization_id',
        'enrollment_year',
        'profile_picture_url',
        'is_active',
        'admin_action_by_id',
        'admin_action_at',
        'admin_action_notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token', // إذا كنت تستخدمه
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // إذا كنت تستخدمه
        'is_active' => 'boolean',
        'admin_action_at' => 'datetime',
        'password_hash' => 'hashed', // Laravel 9+ لتشفير تلقائي عند التعيين (أو استخدم mutator)
    ];

    // إذا كنت تستخدم Laravel < 9 أو تريد تحكمًا أدق في التشفير
    // public function setPasswordHashAttribute($value)
    // {
    //     if ($value) { // تحقق من وجود قيمة لتجنب تشفير قيمة null
    //         $this->attributes['password_hash'] = bcrypt($value);
    //     }
    // }

    // يجب تغيير اسم عمود كلمة المرور الافتراضي إذا كان مختلفًا
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // العلاقات
    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function adminActionBy()
    {
        return $this->belongsTo(AdminUser::class, 'admin_action_by_id');
    }

    public function courseEnrollments()
    {
        return $this->belongsToMany(Course::class, 'student_course_enrollments', 'student_id', 'course_id')
                    ->withPivot('enrollment_date', 'semester_enrolled', 'status', 'grade', 'completion_date', 'notes')
                    ->withTimestamps();
    }

    public function eventRegistrations()
    {
        return $this->belongsToMany(Event::class, 'student_event_registrations', 'student_id', 'event_id')
                    ->withPivot('registration_datetime', 'status', 'attended', 'notes')
                    ->withTimestamps();
    }

    public function receivedNotifications() // التنبيهات التي تم توجيهها لهذا الطالب تحديدًا
    {
        return $this->belongsToMany(Notification::class, 'notification_recipients', 'student_id', 'notification_id')
                    ->withPivot('is_read', 'read_at');
    }
}