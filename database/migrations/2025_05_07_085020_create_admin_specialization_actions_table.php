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
        Schema::create('admin_specialization_actions', function (Blueprint $table) {
            $table->foreignId('admin_id')->constrained('admin_users')->cascadeOnDelete();
            $table->foreignId('specialization_id')->constrained('specializations')->cascadeOnDelete();
            $table->string('action_type', 50)->default('published');
            $table->timestamp('action_at')->useCurrent();
            $table->text('notes')->nullable();
            // Composite primary key
            $table->primary(['admin_id', 'specialization_id', 'action_type', 'action_at']);
            // Laravel لا يدعم `default: current_timestamp` مباشرة في primary key timestamps بهذه الطريقة
            // `action_at` ستحصل على قيمتها عند الإنشاء.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_specialization_actions');
    }
};
