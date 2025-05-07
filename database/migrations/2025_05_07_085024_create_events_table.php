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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->text('description_ar');
            $table->text('description_en')->nullable();
            $table->timestamp('event_start_datetime');
            $table->timestamp('event_end_datetime')->nullable();
            $table->string('location_text')->nullable();
            $table->string('category', 100)->nullable();
            $table->string('main_image_url')->nullable();
            $table->timestamp('registration_deadline')->nullable();
            $table->boolean('requires_registration')->default(false);
            $table->integer('max_attendees')->nullable();
            $table->text('organizer_info')->nullable();
            $table->foreignId('organizing_faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $table->string('status', 50)->default('scheduled');
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
        Schema::dropIfExists('events');
    }
};
