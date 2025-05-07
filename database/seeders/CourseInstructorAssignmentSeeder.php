<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB; // لاستخدام DB::table

class CourseInstructorAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $courseCSE101 = Course::where('code', 'CSE101')->first();
        $courseCSE202 = Course::where('code', 'CSE202')->first();
        $instructorSara = Instructor::where('email', 'sara.hassan@example.com')->first();
        $instructorAhmad = Instructor::where('email', 'ahmad.ali@example.com')->first();

        if ($courseCSE101 && $instructorSara) {
            DB::table('course_instructor_assignments')->insert([
                'course_id' => $courseCSE101->id,
                'instructor_id' => $instructorSara->id,
                'semester_of_assignment' => 'الخريف 2023',
                'role_in_course' => 'Lecturer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($courseCSE202 && $instructorAhmad) {
            DB::table('course_instructor_assignments')->insert([
                'course_id' => $courseCSE202->id,
                'instructor_id' => $instructorAhmad->id,
                'semester_of_assignment' => 'الخريف 2023',
                'role_in_course' => 'Lecturer',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}