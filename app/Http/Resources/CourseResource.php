<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'semester_display_info' => $this->semester_display_info,
            'year_level' => $this->year_level,
            'credits' => $this->credits,
            'is_enrollable' => (bool) $this->is_enrollable,
            'enrollment_capacity' => $this->enrollment_capacity,
            'specialization' => new SpecializationResource($this->whenLoaded('specialization')),
            'resources' => CourseContentResource::collection($this->whenLoaded('resources')), // افترض وجود CourseContentResource
            'instructors' => InstructorResource::collection($this->whenLoaded('instructors')), // المدرسون المعينون للمقرر
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}