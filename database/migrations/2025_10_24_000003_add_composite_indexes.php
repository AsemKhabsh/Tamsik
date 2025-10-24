<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Composite Indexes Migration
 * 
 * إضافة Composite Indexes لتحسين أداء الاستعلامات الشائعة
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة Composite Indexes على جدول articles
        if (Schema::hasTable('articles')) {
            try {
                Schema::table('articles', function (Blueprint $table) {
                    // للاستعلامات التي تبحث حسب الحالة والتصنيف والتاريخ
                    $table->index(['status', 'category_id', 'published_at'], 'idx_articles_status_category_date');
                    
                    // للاستعلامات التي تبحث عن المقالات المميزة المنشورة
                    $table->index(['is_featured', 'status', 'published_at'], 'idx_articles_featured_status_date');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite indexes to articles: ' . $e->getMessage());
            }
        }

        // إضافة Composite Indexes على جدول lectures
        if (Schema::hasTable('lectures')) {
            try {
                Schema::table('lectures', function (Blueprint $table) {
                    // للاستعلامات التي تبحث حسب النشر والموعد والمدينة
                    $table->index(['is_published', 'scheduled_at', 'city'], 'idx_lectures_published_scheduled_city');
                    
                    // للاستعلامات التي تبحث عن المحاضرات المميزة
                    $table->index(['is_featured', 'is_published', 'scheduled_at'], 'idx_lectures_featured_published');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite indexes to lectures: ' . $e->getMessage());
            }
        }

        // إضافة Composite Indexes على جدول fatwas
        if (Schema::hasTable('fatwas')) {
            try {
                Schema::table('fatwas', function (Blueprint $table) {
                    // للاستعلامات التي تبحث عن الفتاوى المنشورة حسب التصنيف
                    $table->index(['is_published', 'category', 'published_at'], 'idx_fatwas_published_category_date');
                    
                    // للاستعلامات التي تبحث عن الفتاوى المميزة
                    $table->index(['is_featured', 'is_published', 'published_at'], 'idx_fatwas_featured_published');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite indexes to fatwas: ' . $e->getMessage());
            }
        }

        // إضافة Indexes على جداول Polymorphic Relations
        if (Schema::hasTable('comments')) {
            try {
                Schema::table('comments', function (Blueprint $table) {
                    // للاستعلامات التي تبحث عن تعليقات مستخدم على محتوى معين
                    $table->index(['commentable_type', 'commentable_id', 'user_id', 'created_at'], 'idx_comments_composite');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite index to comments: ' . $e->getMessage());
            }
        }

        if (Schema::hasTable('likes')) {
            try {
                Schema::table('likes', function (Blueprint $table) {
                    // للاستعلامات التي تبحث عن إعجابات مستخدم
                    $table->index(['likeable_type', 'likeable_id', 'user_id'], 'idx_likes_composite');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite index to likes: ' . $e->getMessage());
            }
        }

        if (Schema::hasTable('favorites')) {
            try {
                Schema::table('favorites', function (Blueprint $table) {
                    // للاستعلامات التي تبحث عن مفضلات مستخدم
                    $table->index(['user_id', 'created_at'], 'idx_favorites_user_date');
                });
            } catch (\Exception $e) {
                // Index already exists
                \Log::warning('Could not add composite index to favorites: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف Composite Indexes من articles
        if (Schema::hasTable('articles')) {
            try {
                Schema::table('articles', function (Blueprint $table) {
                    $table->dropIndex('idx_articles_status_category_date');
                    $table->dropIndex('idx_articles_featured_status_date');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        // حذف Composite Indexes من lectures
        if (Schema::hasTable('lectures')) {
            try {
                Schema::table('lectures', function (Blueprint $table) {
                    $table->dropIndex('idx_lectures_published_scheduled_city');
                    $table->dropIndex('idx_lectures_featured_published');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        // حذف Composite Indexes من fatwas
        if (Schema::hasTable('fatwas')) {
            try {
                Schema::table('fatwas', function (Blueprint $table) {
                    $table->dropIndex('idx_fatwas_published_category_date');
                    $table->dropIndex('idx_fatwas_featured_published');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        // حذف Indexes من Polymorphic Relations
        if (Schema::hasTable('comments')) {
            try {
                Schema::table('comments', function (Blueprint $table) {
                    $table->dropIndex('idx_comments_composite');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('likes')) {
            try {
                Schema::table('likes', function (Blueprint $table) {
                    $table->dropIndex('idx_likes_composite');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('favorites')) {
            try {
                Schema::table('favorites', function (Blueprint $table) {
                    $table->dropIndex('idx_favorites_user_date');
                });
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }
    }
};

