<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InstructorResource; // تأكد من استيراده
use App\Http\Resources\SpecializationSummaryResource; // <<-- استيراد Resource الملخص الجديد

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            // استخدم Resource الملخص الجديد هنا
            'specializations' => SpecializationSummaryResource::collection($this->whenLoaded('specializations')),
            'supervisor' => new InstructorResource($this->whenLoaded('supervisor')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}