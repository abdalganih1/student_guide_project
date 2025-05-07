<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\AdminUser;
use App\Models\Course;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = AdminUser::first(); // أي مدير
        $courseCSE101 = Course::where('code', 'CSE101')->first();

        if ($admin) {
            Notification::create([
                'title_ar' => 'تنبيه هام لجميع الطلاب',
                'title_en' => 'Important Announcement for All Students',
                'body_ar' => 'يرجى العلم بأنه سيتم تحديث نظام التسجيل يوم غد.',
                'body_en' => 'Please be advised that the registration system will be updated tomorrow.',
                'type' => 'important_announcement',
                'sent_by_admin_id' => $admin->id,
                'target_audience_type' => 'all',
                'publish_datetime' => now(),
            ]);
        }

        if ($admin && $courseCSE101) {
            Notification::create([
                'title_ar' => 'تحديث لمقرر مقدمة في البرمجة',
                'title_en' => 'Update for Introduction to Programming Course',
                'body_ar' => 'تم رفع مواد إضافية للمحاضرة الثالثة.',
                'body_en' => 'Additional materials for the third lecture have been uploaded.',
                'type' => 'course_update',
                'sent_by_admin_id' => $admin->id,
                'related_course_id' => $courseCSE101->id,
                'target_audience_type' => 'course_specific', // سيتم استهداف طلاب هذا المقرر
                'publish_datetime' => now(),
            ]);
        }
    }
}