<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin_web')->check();
    }

    public function rules(): array
    {
        return [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'nullable|string',
            'event_start_datetime' => 'required|date_format:Y-m-d\TH:i', // <--- تعديل هنا
            'event_end_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:event_start_datetime', // <--- تعديل هنا
            'location_text' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'registration_deadline' => 'nullable|date_format:Y-m-d\TH:i|before:event_start_datetime', // <--- تعديل هنا
            'requires_registration' => 'required|boolean',
            'max_attendees' => 'nullable|integer|min:0',
            'organizer_info' => 'nullable|string',
            'organizing_faculty_id' => 'nullable|exists:faculties,id',
            'status' => 'required|string|in:scheduled,ongoing,completed,cancelled,draft',
        ];
    }

    // ... (بقية الكود ورسائل الخطأ كما هي أو يمكنك تعديلها لتناسب الصيغة الجديدة إذا أردت)
    public function messages(): array
    {
        return [
            // ...
            'event_start_datetime.date_format' => 'صيغة تاريخ ووقت البدء غير صحيحة (مثال: 2024-12-31T14:30).',
            'event_end_datetime.date_format' => 'صيغة تاريخ ووقت الانتهاء غير صحيحة (مثال: 2024-12-31T14:30).',
            'registration_deadline.date_format' => 'صيغة تاريخ الموعد النهائي للتسجيل غير صحيحة (مثال: 2024-12-31T14:30).',
            // ...
        ];
    }
}