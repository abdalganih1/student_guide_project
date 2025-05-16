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

public function store(Request $request) // يمكنك إنشاء FormRequest لهذا لاحقًا إذا أردت
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
            'publish_datetime' => 'required|date_format:Y-m-d\TH:i', // <--- تعديل هنا
            'expiry_datetime' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:publish_datetime', // <--- تعديل هنا و after_or_equal
            'student_ids' => 'nullable|required_if:target_audience_type,custom_group|required_if:target_audience_type,individual|array',
            'student_ids.*' => 'exists:students,id',
        ], [
            // يمكنك إضافة رسائل خطأ مخصصة هنا
            'publish_datetime.date_format' => 'صيغة تاريخ ووقت النشر غير صحيحة (مثال: 2024-12-31T14:30).',
            'expiry_datetime.date_format' => 'صيغة تاريخ ووقت انتهاء الصلاحية غير صحيحة (مثال: 2024-12-31T14:30).',
            'expiry_datetime.after_or_equal' => 'تاريخ انتهاء الصلاحية يجب أن يكون بعد أو يساوي تاريخ النشر.',
        ]);

        $admin = AdminAuth::guard('admin_web')->user();
        $validatedData['sent_by_admin_id'] = $admin->id;

        // تحويل التنسيق قبل الحفظ في قاعدة البيانات إذا كان عمود قاعدة البيانات هو datetime (Y-m-d H:i:s)
        // Laravel عادةً ما يتعامل مع هذا بشكل جيد عند الحفظ إذا كان الحقل في $casts كـ 'datetime'
        // ولكن للتأكيد، يمكنك القيام بذلك:
        // if (isset($validatedData['publish_datetime'])) {
        //     $validatedData['publish_datetime'] = str_replace('T', ' ', $validatedData['publish_datetime']) . ':00';
        // }
        // if (isset($validatedData['expiry_datetime']) && $validatedData['expiry_datetime']) {
        //     $validatedData['expiry_datetime'] = str_replace('T', ' ', $validatedData['expiry_datetime']) . ':00';
        // }


        $notification = Notification::create($validatedData);

        if (in_array($validatedData['target_audience_type'], ['custom_group', 'individual']) && !empty($validatedData['student_ids'])) {
            foreach ($validatedData['student_ids'] as $studentId) {
                // استخدام create بدلاً من attach إذا كان NotificationRecipients نموذجًا عاديًا
                // وكان لديك fillable مناسب فيه.
                // أو إذا كانت علاقة many-to-many:
                $notification->recipients()->attach($studentId);
            }
        }

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