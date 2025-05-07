<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Specialization;
use App\Models\AdminUser;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $specSE = Specialization::where('name_en', 'Software Engineering')->first();
        $specAI = Specialization::where('name_en', 'Artificial Intelligence')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($specSE && $admin) {
            Course::create([
                'specialization_id' => $specSE->id,
                'code' => 'CSE101',
                'name_ar' => 'مقدمة في البرمجة',
                'name_en' => 'Introduction to Programming',
                'description_ar' => 'أساسيات البرمجة باستخدام لغة بايثون.',
                'semester_display_info' => 'السنة الأولى / الفصل الأول',
                'year_level' => 1,
                'credits' => 3,
                'is_enrollable' => true,
                'created_by_admin_id' => $admin->id,
            ]);

            Course::create([
                'specialization_id' => $specSE->id,
                'code' => 'CSE202',
                'name_ar' => 'هياكل البيانات والخوارزميات',
                'name_en' => 'Data Structures and Algorithms',
                'description_ar' => 'دراسة هياكل البيانات الأساسية والخوارزميات.',
                'semester_display_info' => 'السنة الثانية / الفصل الأول',
                'year_level' => 2,
                'credits' => 4,
                'is_enrollable' => true,
                'created_by_admin_id' => $admin->id,
            ]);
        }

        if ($specAI && $admin) {
            Course::create([
                'specialization_id' => $specAI->id,
                'code' => 'AI301',
                'name_ar' => 'مقدمة في الذكاء الاصطناعي',
                'name_en' => 'Introduction to Artificial Intelligence',
                'description_ar' => 'مفاهيم أساسية في الذكاء الاصطناعي.',
                'semester_display_info' => 'السنة الثالثة / الفصل الأول',
                'year_level' => 3,
                'credits' => 3,
                'is_enrollable' => true,
                'created_by_admin_id' => $admin->id,
            ]);
        }
    }
}