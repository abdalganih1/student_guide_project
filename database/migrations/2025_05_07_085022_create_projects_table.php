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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            // تم حذف المفتاح الأجنبي القديم: $table->foreignId('specialization_id')->constrained('specializations')->cascadeOnDelete();
            // إذا كنت تستخدم عمود specialization_id لأي سبب آخر (مثل التخصص الرئيسي للمشروع)
            // يمكنك تركه كـ integer unsigned، لكنه لن يكون مفتاحاً أجنبياً إلزامياً للتخصص
            $table->unsignedBigInteger('specialization_id')->nullable(); // جعله اختيارياً وليس FK إلزامياً للتخصص الرئيسي إن وجد

            $table->string('title_ar', 500);
            $table->string('title_en', 500)->nullable();
            $table->text('abstract_ar')->nullable();
            $table->text('abstract_en')->nullable();
            $table->integer('year');
            $table->string('semester', 50);
            $table->text('student_names')->nullable();
            $table->foreignId('supervisor_instructor_id')->nullable()->constrained('instructors')->nullOnDelete();
            $table->string('project_type', 100)->nullable();
            $table->text('keywords')->nullable();
            $table->foreignId('created_by_admin_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->foreignId('last_updated_by_admin_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};