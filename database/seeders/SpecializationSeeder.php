<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;
use App\Models\Faculty;
use App\Models\AdminUser;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $facultyInformatics = Faculty::where('name_en', 'Faculty of Informatics Engineering')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($facultyInformatics && $admin) {
            Specialization::create([
                'faculty_id' => $facultyInformatics->id,
                'name_ar' => 'هندسة البرمجيات',
                'name_en' => 'Software Engineering',
                'description_ar' => 'تخصص يركز على تصميم وتطوير وصيانة أنظمة البرمجيات.',
                'description_en' => 'A specialization focused on the design, development, and maintenance of software systems.',
                'status' => 'published',
                'created_by_admin_id' => $admin->id,
            ]);

            Specialization::create([
                'faculty_id' => $facultyInformatics->id,
                'name_ar' => 'الذكاء الاصطناعي',
                'name_en' => 'Artificial Intelligence',
                'description_ar' => 'تخصص يركز على بناء أنظمة تحاكي الذكاء البشري.',
                'description_en' => 'A specialization focused on building systems that emulate human intelligence.',
                'status' => 'published',
                'created_by_admin_id' => $admin->id,
            ]);
        }
    }
}