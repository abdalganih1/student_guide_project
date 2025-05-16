<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUniversityFacilityRequest extends FormRequest {
    public function authorize(): bool { return Auth::guard('admin_web')->check(); }
    public function rules(): array {
        return [
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'media_file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,pdf|max:20480', // مثال: 20MB max, ضبط الأنواع والحجم
            'media_type' => 'required|string|in:image,video,document', // تأكد من أن النوع يطابق الملف
            'category' => 'nullable|string|max:100',
            'faculty_id' => 'nullable|exists:faculties,id',
        ];
    }
     public function messages(): array {
        return [
            'media_file.required' => 'ملف الوسائط مطلوب.',
            'media_file.mimes' => 'نوع الملف غير مدعوم.',
            'media_file.max' => 'حجم الملف كبير جداً (الحد الأقصى 20MB).',
        ];
    }
}