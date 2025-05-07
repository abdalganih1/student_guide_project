<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'event_start_datetime',
        'event_end_datetime',
        'location_text',
        'category',
        'main_image_url',
        'registration_deadline',
        'requires_registration',
        'max_attendees',
        'organizer_info',
        'organizing_faculty_id',
        'status',
        'created_by_admin_id',
        'last_updated_by_admin_id',
    ];

    protected $casts = [
        'requires_registration' => 'boolean',
        'event_start_datetime' => 'datetime',
        'event_end_datetime' => 'datetime',
        'registration_deadline' => 'datetime',
    ];

    // العلاقات
    public function organizingFaculty()
    {
        return $this->belongsTo(Faculty::class, 'organizing_faculty_id');
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }

    public function lastUpdatedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'last_updated_by_admin_id');
    }

    public function registeredStudents()
    {
        return $this->belongsToMany(Student::class, 'student_event_registrations', 'event_id', 'student_id')
                    ->withPivot('registration_datetime', 'status', 'attended', 'notes')
                    ->withTimestamps();
    }

    public function relatedNotifications()
    {
        return $this->hasMany(Notification::class, 'related_event_id');
    }
}