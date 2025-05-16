<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
// استيراد الـ Resource الذي سيمثل موارد المقرر
// افترض أنك أنشأت Resource لنموذج CourseResource، سأسميه CourseMaterialResource كمثال.
// إذا لم تنشئه بعد، ستحتاج لإنشائه: php artisan make:resource CourseMaterialResource
use App\Http\Resources\CourseMaterialResource; // <--- قم بتغيير هذا إذا كان اسم الـ Resource مختلفًا

class CourseResource extends JsonResource // هذا هو Resource لنموذج Course الرئيسي
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
            // هنا التعديل: استخدم الـ Resource الصحيح لموارد المقرر
            'resources' => CourseMaterialResource::collection($this->whenLoaded('resources')), // استخدمنا CourseMaterialResource كمثال
            // إذا لم يكن هناك ربط مباشر بين Course و Instructor في هذا السياق، يمكنك إزالة السطر التالي أو تعديله
            'instructors' => InstructorResource::collection($this->whenLoaded('instructors')), // المدرسون المعينون للمقرر (إذا كانت العلاقة موجودة في النموذج)
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}