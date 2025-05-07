<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class StudentCourseEnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $studentOmar = Student::where('student_university_id', '20230001')->first();
        $studentLayla = Student::where('student_university_id', '20230002')->first();
        $courseCSE101 = Course::where('code', 'CSE101')->first();
        $courseCSE202 = Course::where('code', 'CSE202')->first();

        if ($studentOmar && $courseCSE101) {
            DB::table('student_course_enrollments')->insert([
                'student_id' => $studentOmar->id,
                'course_id' => $courseCSE101->id,
                'enrollment_date' => now()->subMonths(2),
                'semester_enrolled' => 'الخريف 2023',
                'status' => 'enrolled',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($studentOmar && $courseCSE202) {
            DB::table('student_course_enrollments')->insert([
                'student_id' => $studentOmar->id,
                'course_id' => $courseCSE202->id,
                'enrollment_date' => now()->subMonths(2),
                'semester_enrolled' => 'الخريف 2023',
                'status' => 'enrolled',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($studentLayla && $courseCSE101) {
            DB::table('student_course_enrollments')->insert([
                'student_id' => $studentLayla->id,
                'course_id' => $courseCSE101->id,
                'enrollment_date' => now()->subMonths(2),
                'semester_enrolled' => 'الخريف 2023',
                'status' => 'completed',
                'grade' => 'A',
                'completion_date' => now()->subMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}