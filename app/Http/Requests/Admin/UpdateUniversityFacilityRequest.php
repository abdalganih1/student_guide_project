<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUniversityFacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check(); // أو تحقق من صلاحية معينة
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $mediaId = $this->route('university_facility')->id; // اسم البارامتر في المسار

        return [
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,mp4,mov,pdf,doc,docx,xls,xlsx,ppt,pptx|max:51200', // مثال: 50MB max، اضبط الأنواع والحجم
            'media_type' => 'required|string|in:image,video,document', // يجب أن يكون مطلوبًا حتى لو لم يتم تغيير الملف
            'category' => 'nullable|string|max:100',
            'faculty_id' => 'nullable|exists:faculties,id',
            'remove_media_file' => 'nullable|boolean', // لإزالة الملف الحالي
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'media_file.file' => 'يجب أن يكون حقل ملف الوسائط ملفًا.',
            'media_file.mimes' => 'نوع الملف غير مدعوم.',
            'media_file.max' => 'حجم الملف كبير جداً (الحد الأقصى 50MB).',
            'media_type.required' => 'نوع الوسيط مطلوب.',
            'media_type.in' => 'قيمة نوع الوسيط غير صالحة.',
            'faculty_id.exists' => 'الكلية المحددة غير موجودة.',
        ];
    }
}