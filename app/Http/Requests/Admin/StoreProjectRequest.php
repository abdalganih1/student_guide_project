<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProjectRequest extends FormRequest {
    public function authorize(): bool { return Auth::guard('admin_web')->check(); }
    public function rules(): array {
        return [
            'specialization_id' => 'required|exists:specializations,id',
            'title_ar' => 'required|string|max:500',
            'title_en' => 'nullable|string|max:500',
            'abstract_ar' => 'nullable|string',
            'abstract_en' => 'nullable|string',
            'year' => 'required|integer|digits:4',
            'semester' => 'required|string|max:50',
            'student_names' => 'nullable|string',
            'supervisor_instructor_id' => 'nullable|exists:instructors,id',
            'project_type' => 'nullable|string|max:100',
            'keywords' => 'nullable|string',
        ];
    }
}