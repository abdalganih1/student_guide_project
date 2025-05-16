<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'admin_users';

    protected $fillable = [
        'username',
        'name_ar',
        'name_en',
        'email',
        'password_hash', // سيتم تشفيره بواسطة $casts
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password_hash', // جيد إخفاؤه
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password_hash' => 'hashed', // هذه الخاصية ستقوم بالتشفير تلقائيًا
    ];

    // تم إزالة أو تعليق الـ Mutator التالي:
    // public function setPasswordHashAttribute($value)
    // {
    //     $this->attributes['password_hash'] = bcrypt($value);
    // }

    // إذا كان اسم عمود كلمة المرور في قاعدة البيانات هو 'password_hash'
    // يجب أن تخبر Laravel بذلك عند المصادقة.
    // بشكل افتراضي، يتوقع Laravel أن عمود كلمة المرور هو 'password'.
    // أضف هذه الدالة لتحديد اسم عمود كلمة المرور الصحيح للحارس.
    public function getAuthPassword()
    {
        return $this->password_hash;
    }


    // العلاقات (كما هي)
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

    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sent_by_admin_id');
    }
}