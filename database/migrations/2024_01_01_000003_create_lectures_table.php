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
        Schema::create('lectures', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('topic');
            $table->string('category');
            $table->foreignId('speaker_id')->constrained('users')->onDelete('cascade');
            $table->string('location');
            $table->string('city');
            $table->string('venue')->nullable();
            $table->datetime('scheduled_at');
            $table->integer('duration')->nullable(); // بالدقائق
            $table->boolean('is_published')->default(false);
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // weekly, monthly, etc.
            $table->integer('max_attendees')->nullable();
            $table->integer('registered_count')->default(0);
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->json('tags')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('target_audience', ['general', 'men', 'women', 'youth', 'children'])->default('general');
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();

            // الفهارس
            $table->index(['city', 'scheduled_at']);
            $table->index(['speaker_id', 'scheduled_at']);
            $table->index(['is_published', 'scheduled_at']);
            $table->index(['status', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectures');
    }
};
