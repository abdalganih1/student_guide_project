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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->unique();
            $table->string('name_en')->unique()->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            // $table->foreignId('dean_id')->nullable()->constrained('instructors')->nullOnDelete(); // FK to instructors
            // $table->unsignedBigInteger('dean_id')->nullable(); // يطابق نوع id() في Laravel (BigInteger)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
