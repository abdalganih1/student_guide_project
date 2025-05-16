<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            // استخدم foreignId إذا كان النوع في الجدول الأصلي هو unsignedBigInteger
            // أو استخدم foreign مع تحديد النوع والعمود إذا كنت تستخدم int/bigint
            $table->foreignId('dean_id')->nullable()->constrained('instructors')->nullOnDelete();
            // أو هكذا إذا أردت تحديد الاسم يدوياً والعمود كان unsignedBigInteger
            // $table->foreign('dean_id')->references('id')->on('instructors')->nullOnDelete();
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
             // أو هكذا إذا أردت تحديد الاسم يدوياً والعمود كان unsignedBigInteger
            // $table->foreign('faculty_id')->references('id')->on('faculties')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropForeign(['dean_id']);
        });

        Schema::table('instructors', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
        });
    }
};