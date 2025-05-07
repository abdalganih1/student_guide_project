<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'body_ar',
        'body_en',
        'type',
        'sent_by_admin_id',
        'related_course_id',
        'related_event_id',
        'target_audience_type',
        'publish_datetime',
        'expiry_datetime',
    ];

    protected $casts = [
        'publish_datetime' => 'datetime',
        'expiry_datetime' => 'datetime',
    ];

    // العلاقات
    public function sentByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'sent_by_admin_id');
    }

    public function relatedCourse()
    {
        return $this->belongsTo(Course::class, 'related_course_id');
    }

    public function relatedEvent()
    {
        return $this->belongsTo(Event::class, 'related_event_id');
    }

    public function recipients() // الطلاب المستلمون لهذا التنبيه بشكل فردي/مخصص
    {
        return $this->belongsToMany(Student::class, 'notification_recipients', 'notification_id', 'student_id')
                    ->withPivot('is_read', 'read_at');
    }
}