<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StudentEventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;

class EventController extends Controller
{
    /**
     * عرض الفعاليات والمسابقات المتاحة (المجدولة أو الجارية).
     */
    public function index()
    {
        $events = Event::whereIn('status', ['scheduled', 'ongoing'])
                       ->orderBy('event_start_datetime', 'asc')
                       ->get();
        return new EventCollection($events);
    }

    /**
     * عرض تفاصيل فعالية معينة.
     */
    public function show(Event $event)
    {
        // يمكنك هنا إضافة منطق للتحقق مما إذا كان الطالب مسجلاً بالفعل أم لا
        return new EventResource($event);
    }

    /**
     * تقديم طلب تسجيل لفعالية/مسابقة.
     * "الواجهة يوفر نموذجًا موحدًا لـ "طلب" التسجيل، القرار النهائي للتسجيل يتم عبر الإدارة"
     */
    public function register(Request $request, Event $event)
    {
        $student = Auth::user(); // الطالب المسجل دخوله حاليًا

        $request->validate([
            // يمكنك إضافة حقول إضافية إذا كان نموذج التسجيل يتطلبها
            // 'motivation' => 'nullable|string|max:1000',
        ]);

        // التحقق مما إذا كانت الفعالية تتطلب تسجيلًا وما إذا كان التسجيل لا يزال مفتوحًا
        if (!$event->requires_registration || ($event->registration_deadline && $event->registration_deadline < now())) {
            return response()->json(['message' => 'Registration is not required or has closed for this event.'], 400);
        }

        // التحقق مما إذا كان الطالب مسجلاً بالفعل
        $existingRegistration = StudentEventRegistration::where('student_id', $student->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return response()->json(['message' => 'You are already registered or have a pending registration for this event.'], 409); // Conflict
        }

        // التحقق من سعة الفعالية (إذا كانت محددة)
        if ($event->max_attendees) {
            $currentRegistrationsCount = StudentEventRegistration::where('event_id', $event->id)
                                          ->whereIn('status', ['registered', 'pending_approval']) // أو الحالات التي تعتبر شاغرة
                                          ->count();
            if ($currentRegistrationsCount >= $event->max_attendees) {
                return response()->json(['message' => 'This event has reached its maximum capacity.'], 400);
            }
        }

        // إنشاء طلب تسجيل جديد بالحالة الافتراضية "pending_approval"
        $registration = StudentEventRegistration::create([
            'student_id' => $student->id,
            'event_id' => $event->id,
            'registration_datetime' => now(),
            'status' => 'pending_approval', // أو 'registered' إذا كان التسجيل تلقائيًا مبدئيًا
            // 'notes' => $request->input('motivation'), // إذا كان هناك حقل للملاحظات
        ]);

        // يمكنك إرسال إشعار للمدير بمراجعة الطلب هنا (اختياري)

        return response()->json([
            'message' => 'Your registration request has been submitted successfully. It is pending approval.',
            'registration' => $registration,
        ], 201);
    }
}