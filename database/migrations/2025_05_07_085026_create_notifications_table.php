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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('body_ar');
            $table->text('body_en')->nullable();
            $table->string('type', 50)->default('general');
            $table->foreignId('sent_by_admin_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->foreignId('related_course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('related_event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->string('target_audience_type', 50)->default('all');
            $table->timestamp('publish_datetime')->useCurrent();
            $table->timestamp('expiry_datetime')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
