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
        Schema::create('project_specialization', function (Blueprint $table) {
            // لا نحتاج لـ id() عادة في جداول الربط البسيطة
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('specialization_id')->constrained('specializations')->cascadeOnDelete();
            // إضافة مفتاح أساسي مركب لضمان عدم تكرار الربط
            $table->primary(['project_id', 'specialization_id']);

            // يمكنك إضافة created_at/updated_at هنا إذا أردت تتبع متى تم الربط
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_specialization');
    }
};