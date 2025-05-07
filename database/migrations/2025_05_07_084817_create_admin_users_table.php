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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id(); // integer, pk, increment
            $table->string('username', 100)->unique();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('email')->unique();
            $table->string('password_hash'); // سنقوم بتخزين كلمة المرور المشفرة هنا
            $table->string('role', 50)->default('admin');
            $table->boolean('is_active')->default(true);
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_users');
    }
};
