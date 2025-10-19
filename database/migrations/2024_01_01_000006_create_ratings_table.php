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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('ratable'); // للتقييم على الخطب، المقالات، إلخ
            $table->tinyInteger('rating')->unsigned(); // من 1 إلى 5
            $table->text('review')->nullable();
            $table->timestamps();

            // فهرس فريد لمنع التقييم المتكرر من نفس المستخدم
            $table->unique(['user_id', 'ratable_type', 'ratable_id']);
            
            // الفهارس
            $table->index(['rating', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
