<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'student_university_id' => $this->student_university_id,
            'full_name_ar' => $this->full_name_ar,
            'full_name_en' => $this->full_name_en,
            'email' => $this->email,
            'enrollment_year' => $this->enrollment_year,
            'profile_picture_url' => $this->profile_picture_url ? asset('storage/' . $this->profile_picture_url) : null,
            'is_active' => (bool) $this->is_active,
            'specialization' => new SpecializationResource($this->whenLoaded('specialization')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}