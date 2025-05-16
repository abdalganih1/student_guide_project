<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentEventRegistration;
use App\Models\Event;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    /**
     * عرض طلبات التسجيل لفعالية معينة أو جميع الطلبات المعلقة.
     */
    public function index(Request $request)
    {
        $query = StudentEventRegistration::with(['student', 'event'])->latest();

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending_approval'); // عرض الطلبات المعلقة بشكل افتراضي
        }

        $registrations = $query->paginate(15);
        $events = Event::orderBy('title_ar')->get(); // لعرض قائمة الفعاليات للفلترة

        return view('admin.event_registrations.index', compact('registrations', 'events'));
    }

    /**
     * الموافقة على طلب تسجيل.
     */
    public function approve(StudentEventRegistration $registration)
    {
        // تحقق من سعة الفعالية إذا لزم الأمر
        $event = $registration->event;
        if ($event->max_attendees) {
            $approvedCount = StudentEventRegistration::where('event_id', $event->id)
                                        ->where('status', 'registered')
                                        ->count();
            if ($approvedCount >= $event->max_attendees) {
                return redirect()->back()->with('error', 'وصلت الفعالية إلى أقصى سعة تسجيل.');
            }
        }

        $registration->status = 'registered'; // أو 'approved'
        $registration->save();

        // يمكنك إرسال إشعار للطالب بالموافقة هنا

        return redirect()->back()->with('success', 'تمت الموافقة على طلب التسجيل.');
    }

    /**
     * رفض طلب تسجيل.
     */
    public function reject(Request $request, StudentEventRegistration $registration)
    {
        $registration->status = 'rejected';
        // $registration->notes = $request->input('rejection_reason'); // يمكنك إضافة حقل لسبب الرفض
        $registration->save();

        // يمكنك إرسال إشعار للطالب بالرفض هنا

        return redirect()->back()->with('success', 'تم رفض طلب التسجيل.');
    }

    /**
     * عرض تفاصيل طلب تسجيل معين (اختياري).
     */
    public function show(StudentEventRegistration $registration)
    {
        return view('admin.event_registrations.show', compact('registration'));
    }
}