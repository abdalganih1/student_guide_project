<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'abstract_ar' => $this->abstract_ar,
            'abstract_en' => $this->abstract_en,
            'year' => $this->year,
            'semester' => $this->semester,
            'student_names' => $this->student_names,
            'project_type' => $this->project_type,
            'keywords' => $this->keywords,
            'specialization' => new SpecializationResource($this->whenLoaded('specialization')),
            'supervisor' => new InstructorResource($this->whenLoaded('supervisor')),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}