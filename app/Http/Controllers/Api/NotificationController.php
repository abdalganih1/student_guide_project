<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationCollection;

class NotificationController extends Controller
{
    /**
     * جلب التنبيهات للطالب المسجل دخوله.
     * "استلام إشعارات التحديثات الهامة"
     */
    public function index(Request $request)
    {
        $student = Auth::user();

        // جلب التنبيهات العامة (target_audience_type = 'all')
        // وجلب التنبيهات الموجهة للطالب مباشرة عبر NotificationRecipients
        // وجلب التنبيهات الخاصة بالمقررات المسجل بها الطالب
        // وجلب التنبيهات الخاصة بالفعاليات المسجل بها الطالب

        $notifications = Notification::where(function ($query) use ($student) {
            // التنبيهات العامة
            $query->where('target_audience_type', 'all');

            // التنبيهات الموجهة للطالب مباشرة
            $query->orWhereHas('recipients', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            });

            // التنبيهات الخاصة بالمقررات المسجل بها الطالب
            if ($student->courseEnrollments()->exists()) { // تأكد أن لديه تسجيلات
                $enrolledCourseIds = $student->courseEnrollments()->pluck('courses.id'); // جلب IDs المقررات
                $query->orWhere(function ($q) use ($enrolledCourseIds) {
                    $q->where('target_audience_type', 'course_specific')
                      ->whereIn('related_course_id', $enrolledCourseIds);
                });
            }
            // يمكنك إضافة منطق مشابه للفعاليات المسجل بها الطالب
        })
        ->where('publish_datetime', '<=', now()) // التنبيهات التي حان وقت نشرها
        ->where(function ($query) { // التنبيهات التي لم تنته صلاحيتها
            $query->whereNull('expiry_datetime')
                  ->orWhere('expiry_datetime', '>', now());
        })
        ->orderBy('publish_datetime', 'desc')
        ->paginate(15); // استخدام التصفح (pagination)

        return new NotificationCollection($notifications);
    }

    /**
     * وضع علامة "مقروء" على تنبيه معين للطالب.
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        $student = Auth::user();

        // ابحث عن سجل المستلم الخاص بهذا الطالب وهذا التنبيه
        $recipientEntry = NotificationRecipient::firstOrCreate(
            ['notification_id' => $notification->id, 'student_id' => $student->id]
        );

        if (!$recipientEntry->is_read) {
            $recipientEntry->is_read = true;
            $recipientEntry->read_at = now();
            $recipientEntry->save();
        }

        return response()->json(['message' => 'Notification marked as read.']);
    }

    /**
     * وضع علامة "مقروء" على جميع تنبيهات الطالب غير المقروءة (اختياري).
     */
    public function markAllAsRead(Request $request)
    {
        $student = Auth::user();

        // تحديث جميع التنبيهات المخصصة للطالب وغير المقروءة
        NotificationRecipient::where('student_id', $student->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        // هنا يجب أن تفكر كيف ستتعامل مع التنبيهات العامة ('all' أو 'course_specific')
        // هل ستنشئ سجلات في NotificationRecipients لها عند أول عرض أم لا؟
        // إذا لم تنشئ، فإن "markAllAsRead" سينطبق فقط على التنبيهات الفردية.

        return response()->json(['message' => 'All unread notifications marked as read.']);
    }
}