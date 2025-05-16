<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\StudentEventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;
use App\Http\Requests\Api\EventRegistrationApiRequest;
use App\Http\Resources\StudentEventRegistrationResource;

class EventController extends Controller
{
    /**
     * عرض الفعاليات والمسابقات المتاحة (المجدولة أو الجارية).
     */
    public function index(Request $request) // أضف Request إذا كنت ستأخذ عدد العناصر لكل صفحة من الطلب
{
    $perPage = $request->input('per_page', 15); // عدد العناصر لكل صفحة، الافتراضي 15
    $events = Event::whereIn('status', ['scheduled', 'ongoing'])
                   ->orderBy('event_start_datetime', 'asc')
                   ->paginate($perPage); // <--- تغيير هنا
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
    public function register(EventRegistrationApiRequest $request, Event $event)
    {
        $student = Auth::user(); // الطالب المسجل دخوله حاليًا
        $validatedData = $request->validated(); // جلب البيانات المتحقق منها

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
            'status' => 'pending_approval',
            'notes' => $validatedData['motivation'] ?? null, // استخدام القيمة من validatedData
        ]);

        // يمكنك إرسال إشعار للمدير بمراجعة الطلب هنا (اختياري)

        // في دالة register
        return response()->json([
            'message' => 'Your registration request has been submitted successfully. It is pending approval.',
            'registration' => new StudentEventRegistrationResource($registration),
        ], 201);
    }
}