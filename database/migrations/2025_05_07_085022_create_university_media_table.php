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
        Schema::create('university_media', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('file_url', 512);
            $table->string('media_type', 50);
            $table->string('category', 100)->nullable();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            // لاحظ أنك حذفت instructor_id من مخططك الأخير لهذا الجدول، لذا لم أضفه هنا.
            $table->foreignId('uploaded_by_admin_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('university_media');
    }
};
