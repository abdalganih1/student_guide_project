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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            // $table->unsignedBigInteger('faculty_id')->nullable(); // يطابق نوع id() في Laravel (BigInteger)
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('title', 100)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('office_location')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
