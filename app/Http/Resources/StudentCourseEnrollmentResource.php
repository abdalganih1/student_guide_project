<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentCourseEnrollmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this->resource يشير إلى كائن StudentCourseEnrollment
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            // يمكنك جلب تفاصيل الطالب هنا إذا تم تحميل العلاقة
            'student' => new StudentResource($this->whenLoaded('student')), // افترض وجود StudentResource

            'course_id' => $this->course_id,
             // يمكنك جلب تفاصيل المقرر هنا إذا تم تحميل العلاقة
            'course' => new CourseResource($this->whenLoaded('course')), // افترض وجود CourseResource

            // 'enrollment_date' => $this->enrollment_date->toIso8601String(),
            'semester_enrolled' => $this->semester_enrolled,
            'status' => $this->status,
            'grade' => $this->whenNotNull($this->grade), // يعرض فقط إذا لم يكن null
            'completion_date' => $this->whenNotNull($this->completion_date ? $this->completion_date->toDateString() : null), 
            'notes' => $this->whenNotNull($this->notes), // يعرض فقط إذا لم يكن null
            // 'created_at' => $this->created_at->toIso8601String(),
            // 'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}