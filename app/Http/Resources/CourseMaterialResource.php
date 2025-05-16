<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseMaterialResource extends JsonResource // هذا لنموذج CourseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'url' => $this->url,
            'type' => $this->type,
            'description' => $this->description,
            'semester_relevance' => $this->semester_relevance,
            'uploaded_by' => new AdminUserResource($this->whenLoaded('uploadedByAdmin')), // افترض وجود علاقة
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}