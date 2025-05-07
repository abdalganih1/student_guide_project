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
        Schema::create('admin_media_actions', function (Blueprint $table) {
            $table->foreignId('admin_id')->constrained('admin_users')->cascadeOnDelete();
            $table->foreignId('media_id')->constrained('university_media')->cascadeOnDelete();
            $table->string('action_type', 50)->default('published');
            $table->timestamp('action_at')->useCurrent();
            $table->text('notes')->nullable();
            $table->primary(['admin_id', 'media_id', 'action_type', 'action_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_media_actions');
    }
};
