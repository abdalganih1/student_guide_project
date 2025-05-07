<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseResource;
use App\Models\Course;
use App\Models\AdminUser;

class CourseResourceSeeder extends Seeder
{
    public function run(): void
    {
        $courseCSE101 = Course::where('code', 'CSE101')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($courseCSE101 && $admin) {
            CourseResource::create([
                'course_id' => $courseCSE101->id,
                'title_ar' => 'المحاضرة الأولى - مقدمة',
                'title_en' => 'Lecture 1 - Introduction',
                'url' => 'https://example.com/lecture1.pdf',
                'type' => 'lecture_pdf',
                'description' => 'ملف PDF للمحاضرة الأولى.',
                'semester_relevance' => 'الخريف 2023',
                'uploaded_by_admin_id' => $admin->id,
            ]);

            CourseResource::create([
                'course_id' => $courseCSE101->id,
                'title_ar' => 'فيديو شرح المتغيرات',
                'title_en' => 'Variables Explanation Video',
                'url' => 'https://youtube.com/watch?v=xyz',
                'type' => 'lecture_video',
                'description' => 'فيديو يشرح مفهوم المتغيرات في بايثون.',
                'semester_relevance' => 'الخريف 2023',
                'uploaded_by_admin_id' => $admin->id,
            ]);
        }
    }
}