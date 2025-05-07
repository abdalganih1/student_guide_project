<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) { // اسم الجدول students
            $table->id();
            $table->string('student_university_id', 100)->unique();
            $table->string('full_name_ar');
            $table->string('full_name_en')->nullable();
            $table->string('email')->unique();
            $table->string('password_hash')->nullable(); // كلمة المرور للطالب (إذا سيسجل الدخول)
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();
            $table->integer('enrollment_year')->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('admin_action_by_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamp('admin_action_at')->nullable();
            $table->text('admin_action_notes')->nullable();
            // $table->timestamp('email_verified_at')->nullable(); // إذا كنت ستستخدم ميزة التحقق من البريد الإلكتروني في Laravel
            // $table->rememberToken(); // إذا كنت ستستخدم ميزة "تذكرني"
            $table->timestamps();
        });

        // // إذا كنت ستستخدم جدول إعادة تعيين كلمة المرور الافتراضي لـ Laravel للطلاب
        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // إذا كنت ستستخدم جدول الجلسات الافتراضي
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index(); // سيكون student_id
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
        // Schema::dropIfExists('password_reset_tokens'); // إذا أنشأته
        Schema::dropIfExists('sessions'); // إذا أنشأته
    }
};