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
        Schema::table('lectures', function (Blueprint $table) {
            // إضافة عمود views_count لتتبع المشاهدات
            if (!Schema::hasColumn('lectures', 'views_count')) {
                $table->integer('views_count')->default(0)->after('registered_count');
            }
            
            // إضافة عمود is_featured للمحاضرات المميزة
            if (!Schema::hasColumn('lectures', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_published');
            }
        });
        
        // إضافة index للأداء
        Schema::table('lectures', function (Blueprint $table) {
            try {
                $table->index(['is_featured', 'is_published', 'scheduled_at'], 'idx_featured_published_scheduled');
            } catch (\Exception $e) {
                // Index already exists
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            // حذف الـ index أولاً
            try {
                $table->dropIndex('idx_featured_published_scheduled');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
            
            // حذف الأعمدة
            if (Schema::hasColumn('lectures', 'views_count')) {
                $table->dropColumn('views_count');
            }
            
            if (Schema::hasColumn('lectures', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};

