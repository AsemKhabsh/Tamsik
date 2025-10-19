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
        Schema::create('fatwas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('question');
            $table->longText('answer');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->foreignId('questioner_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('scholar_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->json('references')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // الفهارس
            $table->index(['category', 'is_published']);
            $table->index(['scholar_id', 'is_published']);
            $table->index(['is_published', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fatwas');
    }
};
