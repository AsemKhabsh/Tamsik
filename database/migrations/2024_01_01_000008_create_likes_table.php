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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('likeable'); // للإعجاب على الخطب، المقالات، الفتاوى، المحاضرات، إلخ (ينشئ index تلقائياً)
            $table->timestamps();

            // فهرس فريد لمنع الإعجاب المتكرر من نفس المستخدم
            $table->unique(['user_id', 'likeable_type', 'likeable_id']);

            // فهرس إضافي
            $table->index(['user_id', 'created_at']);
            // ملاحظة: morphs() ينشئ index على likeable_type و likeable_id تلقائياً
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};

