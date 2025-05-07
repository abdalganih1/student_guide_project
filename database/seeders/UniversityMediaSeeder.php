<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UniversityMedia;
use App\Models\Faculty;
use App\Models\AdminUser;

class UniversityMediaSeeder extends Seeder
{
    public function run(): void
    {
        $facultyInformatics = Faculty::where('name_en', 'Faculty of Informatics Engineering')->first();
        $admin = AdminUser::where('username', 'contentmanager')->first();

        if ($admin) {
            UniversityMedia::create([
                'title_ar' => 'صورة لمختبر الحاسوب 1',
                'title_en' => 'Photo of Computer Lab 1',
                'file_url' => '/media/lab1.jpg',
                'media_type' => 'image',
                'category' => 'Lab',
                'faculty_id' => $facultyInformatics?->id,
                'uploaded_by_admin_id' => $admin->id,
            ]);
        }
    }
}