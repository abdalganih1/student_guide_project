<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class NotificationResource extends JsonResource {
    public function toArray(Request $request): array {
        // تحقق مما إذا كان الطالب الحالي قد قرأ هذا الإشعار
        $isRead = false;
        $readAt = null;
         if (Auth::guard('sanctum')->check()) {
             $student = Auth::guard('sanctum')->user();
             // ابحث في سجلات المستلمين المباشرة
             $recipientData = $this->recipients()->where('student_id', $student->id)->first(); // يجب تعريف recipients في Notification model
             if ($recipientData) {
                 $isRead = (bool) $recipientData->pivot->is_read;
                 $readAt = $recipientData->pivot->read_at?->toIso8601String();
             }
             // تحتاج إلى منطق إضافي إذا أردت تتبع قراءة التنبيهات العامة
             // أو الخاصة بالمقررات / الفعاليات لكل طالب. الطريقة الأسهل
             // هي إنشاء سجل في NotificationRecipients عند أول مرة يرى الطالب الإشعار العام/المقرر.
        }

        return [
            'id' => $this->id,
            'title_ar' => $this->title_ar,
            'title_en' => $this->title_en,
            'body_ar' => $this->body_ar,
            'body_en' => $this->body_en,
            'type' => $this->type,
            'target_audience_type' => $this->target_audience_type,
            'publish_datetime' => $this->publish_datetime->toIso8601String(),
            'expiry_datetime' => $this->expiry_datetime?->toIso8601String(),
            'related_course' => new CourseResource($this->whenLoaded('relatedCourse')),
            'related_event' => new EventResource($this->whenLoaded('relatedEvent')),
            'sent_by_admin' => new AdminUserResource($this->whenLoaded('sentByAdmin')), // افترض وجود AdminUserResource
            // معلومات خاصة بالطالب الحالي
            'is_read_by_current_user' => $isRead,
            'read_at_by_current_user' => $readAt,
        ];
    }
}