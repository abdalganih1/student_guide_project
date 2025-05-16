<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFacultyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check(); // فقط المدير المسجل يمكنه الإنشاء
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255|unique:faculties,name_ar',
            'name_en' => 'nullable|string|max:255|unique:faculties,name_en',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'dean_id' => 'nullable|exists:instructors,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
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