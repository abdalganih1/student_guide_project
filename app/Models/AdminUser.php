<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // إذا كنت ستستخدمه للمصادقة
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // إذا كنت ستستخدم Sanctum للـ API

class AdminUser extends Authenticatable // أو Model إذا لم يكن للمصادقة
{
    use HasFactory, Notifiable, HasApiTokens; // أضف HasApiTokens إذا لزم الأمر

    protected $table = 'admin_users'; // تحديد اسم الجدول صراحة (اختياري إذا كان الاسم يتبع الاصطلاح)

    protected $fillable = [
        'username',
        'name_ar',
        'name_en',
        'email',
        'password_hash', // يجب تشفيره قبل الحفظ
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime', // إذا أضفته
    ];

    // عند تعيين كلمة المرور، قم بتشفيرها
    public function setPasswordHashAttribute($value)
    {
        $this->attributes['password_hash'] = bcrypt($value);
    }

    // العلاقات
    public function createdSpecializations()
    {
        return $this->hasMany(Specialization::class, 'created_by_admin_id');
    }

    public function lastUpdatedSpecializations()
    {
        return $this->hasMany(Specialization::class, 'last_updated_by_admin_id');
    }

    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by_admin_id');
    }

    // ... أضف بقية العلاقات التي يبدأها المدير
    // مثل specializationActions, courseActions, projectActions, mediaActions, studentActions, eventActions

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sent_by_admin_id');
    }
}