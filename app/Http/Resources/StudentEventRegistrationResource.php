<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentEventRegistrationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this->resource يشير إلى كائن StudentEventRegistration
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'event_title_ar' => $this->whenLoaded('event', $this->event->title_ar, null), // عرض عنوان الفعالية إذا تم تحميل العلاقة
            'student_id' => $this->student_id,
            'student_name_ar' => $this->whenLoaded('student', $this->student->full_name_ar, null), // عرض اسم الطالب إذا تم تحميل العلاقة
            'registration_datetime' => $this->registration_datetime->toIso8601String(),
            'status' => $this->status, // مثل: pending_approval, registered, rejected
            'attended' => $this->whenNotNull($this->attended), // يعرض فقط إذا لم يكن null
            'notes' => $this->notes, // ملاحظات الطالب عند التسجيل (إذا وجدت)
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // يمكنك إضافة المزيد من التفاصيل من الفعالية أو الطالب إذا لزم الأمر
            // 'event_details' => new EventResource($this->whenLoaded('event')),
            // 'student_details' => new StudentResource($this->whenLoaded('student')),
        ];
    }
}