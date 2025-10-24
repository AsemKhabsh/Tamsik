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
        // إضافة عمود likes_count لجدول sermons
        Schema::table('sermons', function (Blueprint $table) {
            if (!Schema::hasColumn('sermons', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('views_count');
            }
        });

        // إضافة عمود likes_count لجدول fatwas
        Schema::table('fatwas', function (Blueprint $table) {
            if (!Schema::hasColumn('fatwas', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('views_count');
            }
        });

        // إضافة عمود likes_count لجدول lectures
        Schema::table('lectures', function (Blueprint $table) {
            if (!Schema::hasColumn('lectures', 'likes_count')) {
                $table->integer('likes_count')->default(0)->after('views_count');
            }
        });

        // إضافة فهارس للأداء
        Schema::table('sermons', function (Blueprint $table) {
            try {
                $table->index('likes_count');
            } catch (\Exception $e) {
                // Index already exists
            }
        });

        Schema::table('fatwas', function (Blueprint $table) {
            try {
                $table->index('likes_count');
            } catch (\Exception $e) {
                // Index already exists
            }
        });

        Schema::table('lectures', function (Blueprint $table) {
            try {
                $table->index('likes_count');
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
        // حذف الفهارس أولاً
        Schema::table('sermons', function (Blueprint $table) {
            try {
                $table->dropIndex(['likes_count']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        });

        Schema::table('fatwas', function (Blueprint $table) {
            try {
                $table->dropIndex(['likes_count']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        });

        Schema::table('lectures', function (Blueprint $table) {
            try {
                $table->dropIndex(['likes_count']);
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        });

        // حذف الأعمدة
        Schema::table('sermons', function (Blueprint $table) {
            if (Schema::hasColumn('sermons', 'likes_count')) {
                $table->dropColumn('likes_count');
            }
        });

        Schema::table('fatwas', function (Blueprint $table) {
            if (Schema::hasColumn('fatwas', 'likes_count')) {
                $table->dropColumn('likes_count');
            }
        });

        Schema::table('lectures', function (Blueprint $table) {
            if (Schema::hasColumn('lectures', 'likes_count')) {
                $table->dropColumn('likes_count');
            }
        });
    }
};

