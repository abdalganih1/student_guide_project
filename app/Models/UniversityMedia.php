<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityMedia extends Model
{
    use HasFactory;

    protected $table = 'university_media';

    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'file_url',
        'media_type',
        'category',
        'faculty_id',
        // 'instructor_id', // تم حذفه من مخططك الأخير
        'uploaded_by_admin_id',
    ];

    // العلاقات
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    // إذا كنت قد أبقيت على instructor_id
    // public function instructor()
    // {
    //     return $this->belongsTo(Instructor::class);
    // }

    public function uploadedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'uploaded_by_admin_id');
    }
    
    // إذا كنت قد أبقيت على جدول InstructorMediaAssociations
    // public function associatedInstructors()
    // {
    //     return $this->belongsToMany(Instructor::class, 'instructor_media_associations', 'media_id', 'instructor_id')
    //                 ->withPivot('description')
    //                 ->withTimestamps('created_at');
    // }
}