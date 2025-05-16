<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student; // تأكد من أن هذا هو نموذج الطالب الصحيح
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $specSE = Specialization::where('name_en', 'Software Engineering')->first();

        Student::create([
            'student_university_id' => '20230001',
            'full_name_ar' => 'عمر خالد',
            'full_name_en' => 'Omar Khaled',
            'email' => 'omar.khaled@student.example.com',
            'password_hash' => Hash::make('password'), // كلمة مرور للطالب
            'specialization_id' => $specSE?->id,
            'enrollment_year' => 2023,
            'is_active' => true,
        ]);

        Student::create([
            'student_university_id' => '20230002',
            'full_name_ar' => 'ليلى أحمد',
            'full_name_en' => 'Layla Ahmad',
            'email' => 'layla.ahmad@student.example.com',
            'password_hash' => Hash::make('password'),
            'specialization_id' => $specSE?->id,
            'enrollment_year' => 2023,
            'is_active' => true,
        ]);
    }
}