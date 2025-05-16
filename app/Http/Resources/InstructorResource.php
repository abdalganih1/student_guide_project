<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'title' => $this->title,
            'email' => $this->email,
            'office_location' => $this->office_location,
            'bio' => $this->bio,
            'profile_picture_url' => $this->profile_picture_url ? asset('storage/' . $this->profile_picture_url) : null, // إذا كانت الصور مخزنة محليًا
            'is_active' => (bool) $this->is_active,
            'faculty' => new FacultyResource($this->whenLoaded('faculty')),
            // يمكنك إضافة المقررات التي يدرسها إذا أردت عرضها في تفاصيل المدرس
            // 'courses_teaching' => CourseResource::collection($this->whenLoaded('courseAssignments')),
        ];
    }
}