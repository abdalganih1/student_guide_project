<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check();
    }

    public function rules(): array
    {
         // لا نحتاج لـ $projectId هنا للتخصصات
        return [
            'specialization_ids' => 'required|array',
            'specialization_ids.*' => 'exists:specializations,id',
            // تم إزالة قاعدة التحقق من specialization_id
            'title_ar' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'year' => 'required|integer|digits:4|min:2000|max:' . (date('Y') + 5),
            'semester' => 'required|string|in:الخريف,الربيع',
            'student_names' => 'nullable|string',
            'supervisor_instructor_id' => 'nullable|exists:instructors,id',
            'project_type' => 'nullable|string|max:100',
            'keywords' => 'nullable|string',
        ];
    }

     public function messages(): array
    {
        return [
            'specialization_ids.required' => 'يجب اختيار اختصاص واحد على الأقل للمشروع.',
            'specialization_ids.array' => 'حقل الاختصاصات يجب أن يكون مصفوفة.',
            'specialization_ids.*.exists' => 'أحد الاختصاصات المختارة غير موجود.',
            'title_ar.required' => 'عنوان المشروع باللغة العربية مطلوب.',
            'year.required' => 'سنة المشروع مطلوبة.',
            'semester.required' => 'الفصل الدراسي للمشروع مطلوب.',
            'semester.in' => 'قيمة الفصل الدراسي غير صالحة.',
            'supervisor_instructor_id.exists' => 'المشرف المحدد غير موجود.',
        ];
    }
}