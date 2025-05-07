<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        Faculty::create([
            'name_ar' => 'كلية الهندسة المعلوماتية',
            'name_en' => 'Faculty of Informatics Engineering',
            'description_ar' => 'كلية متخصصة في علوم وهندسة الحاسوب وتكنولوجيا المعلومات.',
            'description_en' => 'A faculty specialized in computer science, engineering, and information technology.',
            // 'dean_id' => 1, // افترض أن المدرس الأول سيكون العميد، سيتم تعيينه لاحقًا أو تركه null
        ]);

        Faculty::create([
            'name_ar' => 'كلية إدارة الأعمال',
            'name_en' => 'Faculty of Business Administration',
            'description_ar' => 'كلية متخصصة في علوم الإدارة والأعمال.',
            'description_en' => 'A faculty specialized in management and business sciences.',
        ]);
        Faculty::create([
            'name_ar' => 'كلية إدارة الأعمال',
            'name_en' => 'Faculty of Business Administration',
            'description_ar' => 'كلية متخصصة في علوم الإدارة والأعمال.',
            'description_en' => 'A faculty specialized in management and business sciences.',
        ]);
    }
}