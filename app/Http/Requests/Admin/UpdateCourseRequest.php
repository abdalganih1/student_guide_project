<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check(); // أو يمكنك إضافة تحقق من صلاحية معينة
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseId = $this->route('course')->id; // الحصول على id المقرر من المسار

        return [
            'specialization_id' => 'required|exists:specializations,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'code')->ignore($courseId),
            ],
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'semester_display_info' => 'required|string|max:100',
            'year_level' => 'nullable|integer|min:1|max:7',
            'credits' => 'nullable|integer|min:0',
            'is_enrollable' => 'required|boolean',
            'enrollment_capacity' => 'nullable|integer|min:0',
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
            'code.required' => 'رمز المقرر مطلوب.',
            'code.unique' => 'رمز المقرر هذا مستخدم مسبقاً.',
            'name_ar.required' => 'اسم المقرر باللغة العربية مطلوب.',
            'semester_display_info.required' => 'معلومات عرض الفصل الدراسي مطلوبة.',
            'year_level.integer' => 'مستوى السنة يجب أن يكون رقمًا صحيحًا.',
            'year_level.min' => 'مستوى السنة يجب أن يكون 1 على الأقل.',
            'credits.integer' => 'عدد الساعات المعتمدة يجب أن يكون رقمًا صحيحًا.',
            'credits.min' => 'عدد الساعات المعتمدة لا يمكن أن يكون سالبًا.',
            'is_enrollable.required' => 'حالة إمكانية التسجيل مطلوبة.',
            'is_enrollable.boolean' => 'قيمة حقل إمكانية التسجيل غير صحيحة.',
            'enrollment_capacity.integer' => 'سعة التسجيل يجب أن تكون رقمًا صحيحًا.',
            'enrollment_capacity.min' => 'سعة التسجيل لا يمكن أن تكون سالبة.',
        ];
    }
}