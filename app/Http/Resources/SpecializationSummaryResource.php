<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationSummaryResource extends JsonResource // هذا هو Resource لملخص Specialization
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $this->resource يشير إلى كائن Specialization
        return [
            'id' => $this->id,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            // يمكنك إضافة حقول أخرى هنا إذا أردت إظهارها في الملخص
            // 'faculty' => new FacultySummary($this->whenLoaded('faculty')), // إذا أردت ملخص الكلية أيضاً
        ];
    }
}