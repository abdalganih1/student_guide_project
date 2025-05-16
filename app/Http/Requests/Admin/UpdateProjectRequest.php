<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
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
        // $projectId = $this->route('project')->id; // لا نحتاج عادةً لـ ignore unique هنا لعناوين المشاريع

        return [
            'specialization_id' => 'required|exists:specializations,id',
            'title_ar' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'year' => 'required|integer|digits:4|min:2000|max:' . (date('Y') + 5), // سنة معقولة
            'semester' => 'required|string|in:الخريف,الربيع', // تأكد من أن هذه القيم تطابق ما تستخدمه
            'student_names' => 'nullable|string',
            'supervisor_instructor_id' => 'nullable|exists:instructors,id',
            'project_type' => 'nullable|string|max:100',
            'keywords' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'specialization_id.required' => 'حقل الاختصاص مطلوب.',
            'specialization_id.exists' => 'الاختصاص المحدد غير موجود.',
            'title_ar.required' => 'عنوان المشروع باللغة العربية مطلوب.',
            'title_ar.max' => 'عنوان المشروع (عربي) طويل جداً.',
            'year.required' => 'سنة المشروع مطلوبة.',
            'year.integer' => 'سنة المشروع يجب أن تكون رقمًا صحيحًا.',
            'year.digits' => 'سنة المشروع يجب أن تتكون من 4 أرقام.',
            'semester.required' => 'الفصل الدراسي للمشروع مطلوب.',
            'semester.in' => 'قيمة الفصل الدراسي غير صالحة.',
            'supervisor_instructor_id.exists' => 'المشرف المحدد غير موجود.',
        ];
    }
}