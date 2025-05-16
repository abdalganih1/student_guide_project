<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateStudentRequest extends FormRequest
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
        $studentId = $this->route('student')->id; // الحصول على id الطالب من المسار

        return [
            'student_university_id' => [
                'required',
                'string',
                'max:100',
                Rule::unique('students', 'student_university_id')->ignore($studentId),
            ],
            'full_name_ar' => 'required|string|max:255',
            'full_name_en' => 'nullable|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($studentId),
            ],
            'password' => ['nullable', 'string', Password::min(6), 'confirmed'], // كلمة المرور اختيارية عند التحديث
            'specialization_id' => 'nullable|exists:specializations,id',
            'enrollment_year' => 'nullable|integer|digits:4|min:1980|max:' . (date('Y') + 1),
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_profile_picture' => 'nullable|boolean',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'student_university_id.required' => 'الرقم الجامعي للطالب مطلوب.',
            'student_university_id.unique' => 'هذا الرقم الجامعي مستخدم مسبقاً.',
            'full_name_ar.required' => 'اسم الطالب باللغة العربية مطلوب.',
            'email.required' => 'البريد الإلكتروني للطالب مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير مطابق.',
            'specialization_id.exists' => 'الاختصاص المحدد غير موجود.',
            'enrollment_year.integer' => 'سنة الالتحاق يجب أن تكون رقمًا صحيحًا.',
            'enrollment_year.digits' => 'سنة الالتحاق يجب أن تتكون من 4 أرقام.',
            'profile_picture.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'is_active.required' => 'حالة نشاط الطالب مطلوبة.',
        ];
    }
}