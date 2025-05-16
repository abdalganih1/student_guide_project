<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage; // لاستخدام Storage::url

class UniversityMediaResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'file_url' => $this->file_url ? Storage::disk('public')->url($this->file_url) : null, // الحصول على الرابط العام
            'media_type' => $this->media_type,
            'category' => $this->category,
            'faculty' => new FacultyResource($this->whenLoaded('faculty')),
            'uploaded_at' => $this->created_at->toDateTimeString(),
        ];
    }
}