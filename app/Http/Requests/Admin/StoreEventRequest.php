<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEventRequest extends FormRequest {
    public function authorize(): bool { return Auth::guard('admin_web')->check(); }
    public function rules(): array {
        return [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'event_start_datetime' => 'required|date_format:Y-m-d H:i:s', // تأكد من أن تنسيق الواجهة الأمامية يطابق هذا
            'event_end_datetime' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:event_start_datetime',
            'location_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'main_image_url' => 'nullable|string|max:255', // أو 'image' إذا كان ملفًا
            'registration_deadline' => 'nullable|date_format:Y-m-d H:i:s|before:event_start_datetime',
            'requires_registration' => 'required|boolean',
            'max_attendees' => 'nullable|integer|min:0',
            'organizer_info' => 'nullable|string',
            'organizing_faculty_id' => 'nullable|exists:faculties,id',
            'status' => 'required|string|in:scheduled,ongoing,completed,cancelled,draft', // إضافة draft إذا لزم الأمر
        ];
    }
}