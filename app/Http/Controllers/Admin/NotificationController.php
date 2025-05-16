<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Student;
use App\Models\Course;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AdminAuth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index()
    {
        $notifications = Notification::with('sentByAdmin')->latest()->paginate(15);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $courses = Course::orderBy('name_ar')->get();
        $events = Event::orderBy('title_ar')->get();
        // يمكنك جلب قائمة بالطلاب إذا أردت اختيار فردي/مجموعة مخصصة (قد تكون القائمة كبيرة)
        return view('admin.notifications.create', compact('courses', 'events'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'body_ar' => 'required|string',
            'body_en' => 'nullable|string',
            'type' => 'required|string|max:50',
            'target_audience_type' => 'required|string|in:all,course_specific,custom_group,individual',
            'related_course_id' => 'nullable|required_if:target_audience_type,course_specific|exists:courses,id',
            'related_event_id' => 'nullable|exists:events,id',
            'publish_datetime' => 'required|date_format:Y-m-d H:i:s', // أو 'date' فقط
            'expiry_datetime' => 'nullable|date_format:Y-m-d H:i:s|after:publish_datetime',
            'student_ids' => 'nullable|required_if:target_audience_type,custom_group|required_if:target_audience_type,individual|array',
            'student_ids.*' => 'exists:students,id', // تحقق من وجود كل طالب
        ]);

        $admin = AdminAuth::guard('admin_web')->user();
        $validatedData['sent_by_admin_id'] = $admin->id;

        $notification = Notification::create($validatedData);

        // إذا كان الاستهداف فرديًا أو لمجموعة مخصصة، قم بإنشاء سجلات في NotificationRecipients
        if (in_array($validatedData['target_audience_type'], ['custom_group', 'individual']) && !empty($validatedData['student_ids'])) {
            foreach ($validatedData['student_ids'] as $studentId) {
                $notification->recipients()->attach($studentId); // يفترض أن علاقة recipients هي belongsToMany
            }
        }

        // يمكنك هنا إضافة منطق لإرسال الإشعار الفعلي (مثل push notifications) إذا لزم الأمر

        return redirect()->route('admin.notifications.index')->with('success', 'تم إرسال/جدولة التنبيه بنجاح.');
    }

    public function show(Notification $notification)
    {
        $notification->load('sentByAdmin', 'relatedCourse', 'relatedEvent', 'recipients');
        return view('admin.notifications.show', compact('notification'));
    }

    // لا يوجد عادةً تعديل أو حذف للتنبيهات المرسلة، لكن يمكن إضافتهما إذا لزم الأمر
    // public function edit(Notification $notification) { ... }
    // public function update(Request $request, Notification $notification) { ... }
    // public function destroy(Notification $notification) { ... }
}