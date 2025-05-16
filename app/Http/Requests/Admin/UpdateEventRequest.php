<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
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
            'remove_main_image' => 'nullable|boolean',
            'registration_deadline' => 'nullable|date_format:Y-m-d\TH:i|before:event_start_datetime', // <--- تعديل هنا
            'requires_registration' => 'required|boolean',
            'max_attendees' => 'nullable|integer|min:0',
            'organizer_info' => 'nullable|string',
            'organizing_faculty_id' => 'nullable|exists:faculties,id',
            'status' => 'required|string|in:scheduled,ongoing,completed,cancelled,draft',
        ];
    }
    // ... (رسائل الخطأ يمكن تعديلها أيضًا)

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title_ar.required' => 'عنوان الفعالية باللغة العربية مطلوب.',
            'description_ar.required' => 'وصف الفعالية باللغة العربية مطلوب.',
            'event_start_datetime.required' => 'تاريخ ووقت بدء الفعالية مطلوب.',
            'event_start_datetime.date_format' => 'صيغة تاريخ ووقت البدء غير صحيحة.',
            'event_end_datetime.date_format' => 'صيغة تاريخ ووقت الانتهاء غير صحيحة.',
            'event_end_datetime.after_or_equal' => 'تاريخ ووقت الانتهاء يجب أن يكون بعد أو يساوي تاريخ البدء.',
            'main_image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'main_image.mimes' => 'نوع الصورة غير مدعوم.',
            'main_image.max' => 'حجم الصورة كبير جداً (الحد الأقصى 2MB).',
            'registration_deadline.date_format' => 'صيغة تاريخ الموعد النهائي للتسجيل غير صحيحة.',
            'registration_deadline.before' => 'الموعد النهائي للتسجيل يجب أن يكون قبل تاريخ بدء الفعالية.',
            'requires_registration.required' => 'تحديد ما إذا كانت الفعالية تتطلب تسجيلًا مطلوب.',
            'max_attendees.integer' => 'الحد الأقصى للحضور يجب أن يكون رقمًا صحيحًا.',
            'organizing_faculty_id.exists' => 'الكلية المنظمة المحددة غير موجودة.',
            'status.required' => 'حالة الفعالية مطلوبة.',
            'status.in' => 'قيمة حالة الفعالية غير صالحة.',
        ];
    }
}