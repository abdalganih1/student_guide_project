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
        Schema::create('student_event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->timestamp('registration_datetime')->useCurrent();
            $table->string('status', 50)->default('registered');
            $table->boolean('attended')->nullable()->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'event_id'], 'unique_student_event_registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_event_registrations');
    }
};
