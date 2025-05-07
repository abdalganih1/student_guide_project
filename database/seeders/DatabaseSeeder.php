<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            FacultySeeder::class,
            InstructorSeeder::class, // يعتمد على Faculties (لتحديث العميد)
            SpecializationSeeder::class, // يعتمد على Faculties, AdminUsers
            CourseSeeder::class,         // يعتمد على Specializations, AdminUsers
            CourseResourceSeeder::class, // يعتمد على Courses, AdminUsers
            CourseInstructorAssignmentSeeder::class, // يعتمد على Courses, Instructors
            StudentSeeder::class,        // يعتمد على Specializations
            StudentCourseEnrollmentSeeder::class, // يعتمد على Students, Courses
            ProjectSeeder::class,        // يعتمد على Specializations, Instructors, AdminUsers
            EventSeeder::class,          // يعتمد على Faculties, AdminUsers
            StudentEventRegistrationSeeder::class, // يعتمد على Students, Events (إذا تم إنشاؤه)
            UniversityMediaSeeder::class, // يعتمد على Faculties, AdminUsers
            NotificationSeeder::class,   // يعتمد على AdminUsers, Courses, Events
            NotificationRecipientSeeder::class, // يعتمد على Notifications, Students (إذا تم إنشاؤه)
            // يمكنك إضافة الـ Admin Action Seeders هنا إذا أردت
        ]);
    }
}
