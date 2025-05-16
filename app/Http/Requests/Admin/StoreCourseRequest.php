<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check();
    }

    public function rules(): array
    {
        return [
            'specialization_id' => 'required|exists:specializations,id',
            'code' => 'required|string|max:50|unique:courses,code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'semester_display_info' => 'required|string|max:100',
            'year_level' => 'nullable|integer|min:1|max:7', // افترض 7 سنوات كحد أقصى
            'credits' => 'nullable|integer|min:0',
            'is_enrollable' => 'required|boolean',
            'enrollment_capacity' => 'nullable|integer|min:0',
        ];
    }
}