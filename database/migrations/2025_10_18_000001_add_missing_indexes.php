<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Missing Indexes Migration
 * 
 * إضافة Indexes ناقصة لتحسين الأداء
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة Index على users.email للبحث السريع
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email');
            });
        } catch (\Exception $e) {
            // Index already exists
        }

        // إضافة Index على sermons.slug للوصول السريع
        try {
            Schema::table('sermons', function (Blueprint $table) {
                $table->index('slug');
            });
        } catch (\Exception $e) {
            // Index already exists
        }

        // إضافة Index على articles.slug للوصول السريع
        if (Schema::hasTable('articles')) {
            try {
                Schema::table('articles', function (Blueprint $table) {
                    $table->index('slug');
                });
            } catch (\Exception $e) {
                // Index already exists
            }
        }

        // إضافة Index على lectures.scheduled_at للفرز والبحث
        if (Schema::hasTable('lectures')) {
            try {
                Schema::table('lectures', function (Blueprint $table) {
                    $table->index('scheduled_at');
                });
            } catch (\Exception $e) {
                // Index already exists
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['email']);
            });
        } catch (\Exception $e) {
            // Index doesn't exist
        }

        try {
            Schema::table('sermons', function (Blueprint $table) {
                $table->dropIndex(['slug']);
            });
        } catch (\Exception $e) {
            // Index doesn't exist
        }

        if (Schema::hasTable('articles')) {
            try {
                Schema::table('articles', function (Blueprint $table) {
                    $table->dropIndex(['slug']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('lectures')) {
            try {
                Schema::table('lectures', function (Blueprint $table) {
                    $table->dropIndex(['scheduled_at']);
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }
    }
};

