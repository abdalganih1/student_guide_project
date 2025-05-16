<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateSpecializationRequest extends FormRequest
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
        $specializationId = $this->route('specialization')->id; // الحصول على id الاختصاص من المسار

        return [
            'faculty_id' => 'required|exists:faculties,id',
            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('specializations', 'name_ar')
                    ->ignore($specializationId)
                    ->where('faculty_id', $this->faculty_id),
            ],
            'name_en' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('specializations', 'name_en')
                    ->ignore($specializationId)
                    ->where('faculty_id', $this->faculty_id)
                    ->whereNotNull('name_en'),
            ],
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'status' => 'required|string|in:draft,published,archived',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'faculty_id.required' => 'حقل الكلية مطلوب.',
            'faculty_id.exists' => 'الكلية المحددة غير موجودة.',
            'name_ar.required' => 'اسم الاختصاص باللغة العربية مطلوب.',
            'name_ar.unique' => 'اسم الاختصاص (عربي) موجود مسبقاً ضمن هذه الكلية.',
            'name_en.unique' => 'اسم الاختصاص (إنجليزي) موجود مسبقاً ضمن هذه الكلية.',
            'description_ar.required' => 'وصف الاختصاص باللغة العربية مطلوب.',
            'status.required' => 'حالة الاختصاص مطلوبة.',
            'status.in' => 'قيمة حالة الاختصاص غير صالحة.',
        ];
    }
}