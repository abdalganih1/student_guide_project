<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check();
    }

    public function rules(): array
    {
        $facultyId = $this->route('faculty')->id; // الحصول على id الكلية من المسار

        return [
            'name_ar' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faculties', 'name_ar')->ignore($facultyId),
            ],
            'name_en' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('faculties', 'name_en')->ignore($facultyId)->whereNotNull('name_en'),
            ],
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'dean_id' => 'nullable|exists:instructors,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name_ar.required' => 'اسم الكلية باللغة العربية مطلوب.',
            'name_ar.unique' => 'اسم الكلية باللغة العربية موجود مسبقاً.',
            'name_en.unique' => 'اسم الكلية باللغة الإنجليزية موجود مسبقاً.',
            'dean_id.exists' => 'المعرف الخاص بالعميد غير صحيح.',
        ];
    }
}