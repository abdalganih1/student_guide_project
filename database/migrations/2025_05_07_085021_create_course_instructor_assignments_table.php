<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('course_instructor_assignments', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
        //     $table->foreignId('instructor_id')->constrained('instructors')->cascadeOnDelete();
        //     $table->string('semester_of_assignment', 50);
        //     $table->string('role_in_course', 50)->nullable()->default('Lecturer');
        //     $table->timestamps();
        //     $table->unique(['course_id', 'instructor_id', 'semester_of_assignment'], 'unique_course_instructor_semester_assignment'); // اسم القيد أطول قليلاً ليكون وصفيًا
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('course_instructor_assignments');
    }
};
