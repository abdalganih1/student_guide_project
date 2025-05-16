<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $instructorId = $this->route('instructor')->id; // الحصول على id المدرس من المسار

        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('instructors', 'email')->ignore($instructorId),
            ],
            'title' => 'nullable|string|max:100',
            'faculty_id' => 'nullable|exists:faculties,id',
            'office_location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
            'remove_profile_picture' => 'nullable|boolean', // حقل اختياري لإزالة الصورة
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'اسم المدرس باللغة العربية مطلوب.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'faculty_id.exists' => 'الكلية المحددة غير موجودة.',
            'profile_picture.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'profile_picture.mimes' => 'نوع الصورة غير مدعوم (الأنواع المدعومة: jpeg, png, jpg, gif, svg).',
            'profile_picture.max' => 'حجم الصورة يجب ألا يتجاوز 2MB.',
            'is_active.required' => 'حالة النشاط مطلوبة.',
        ];
    }
}