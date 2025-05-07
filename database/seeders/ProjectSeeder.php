<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project; // اسم النموذج هو Project
use App\Models\Specialization;
use App\Models\Instructor;
use App\Models\AdminUser;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $specSE = Specialization::where('name_en', 'Software Engineering')->first();
        $instructorAhmad = Instructor::where('email', 'ahmad.ali@example.com')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($specSE && $instructorAhmad && $admin) {
            Project::create([
                'specialization_id' => $specSE->id,
                'title_ar' => 'نظام إدارة دليل الطالب',
                'title_en' => 'Student Guide Management System',
                'abstract_ar' => 'ملخص مشروع نظام إدارة دليل الطالب...',
                'year' => 2024,
                'semester' => 'الربيع',
                'student_names' => 'فريق العمل أ',
                'supervisor_instructor_id' => $instructorAhmad->id,
                'project_type' => 'تطويري',
                'keywords' => 'دليل الطالب, لارافيل, موبايل',
                'created_by_admin_id' => $admin->id,
            ]);
        }
    }
}