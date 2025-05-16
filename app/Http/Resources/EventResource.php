<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EventResource extends JsonResource {
    public function toArray(Request $request): array {
        // تحقق مما إذا كان المستخدم الحالي (الطالب) مسجلاً
        $isRegistered = false;
        $registrationStatus = null;
        if (Auth::guard('sanctum')->check()) { // تحقق من وجود مستخدم مسجل عبر Sanctum
             $student = Auth::guard('sanctum')->user();
             $registration = $this->registeredStudents()->where('student_id', $student->id)->first(); // يجب أن تكون العلاقة معرفة في نموذج Event
             if ($registration) {
                 $isRegistered = true;
                 $registrationStatus = $registration->pivot->status; // الوصول للحالة من الجدول الوسيط
             }
        }

        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'description_ar' => $this->description_ar,
            'description_en' => $this->description_en,
            'event_start_datetime' => $this->event_start_datetime->toIso8601String(),
            'event_end_datetime' => $this->event_end_datetime?->toIso8601String(),
            'location_text' => $this->location_text,
            'category' => $this->category,
            'main_image_url' => $this->main_image_url ? asset('storage/' . $this->main_image_url) : null,
            'registration_deadline' => $this->registration_deadline?->toIso8601String(),
            'requires_registration' => (bool) $this->requires_registration,
            'max_attendees' => $this->max_attendees,
            'organizer_info' => $this->organizer_info,
            'status' => $this->status,
            'organizing_faculty' => new FacultyResource($this->whenLoaded('organizingFaculty')),
            // معلومات إضافية للطالب المسجل
            'is_registered_by_current_user' => $isRegistered,
            'current_user_registration_status' => $registrationStatus,
            'is_registration_open' => $this->requires_registration && (!$this->registration_deadline || $this->registration_deadline > now()),
        ];
    }
}