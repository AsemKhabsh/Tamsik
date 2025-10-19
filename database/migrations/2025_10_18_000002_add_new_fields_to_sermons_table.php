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
        Schema::table('sermons', function (Blueprint $table) {
            // إضافة حقول جديدة
            $table->date('sermon_date')->nullable()->after('title');
            $table->string('occasion')->nullable()->after('sermon_date');
            $table->longText('main_content')->nullable()->after('introduction');
            $table->json('metadata')->nullable()->after('references');
            $table->string('slug')->nullable()->after('title');
            $table->string('status')->default('draft')->after('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sermons', function (Blueprint $table) {
            $table->dropColumn(['sermon_date', 'occasion', 'main_content', 'metadata', 'slug', 'status']);
        });
    }
};

