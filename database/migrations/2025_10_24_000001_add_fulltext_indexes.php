<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Add Full-Text Indexes Migration
 * 
 * إضافة Full-Text Indexes لتحسين أداء البحث
 * التأثير المتوقع: تحسين سرعة البحث بنسبة 70-90%
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة Full-Text Index على جدول sermons
        if (Schema::hasTable('sermons')) {
            try {
                DB::statement('ALTER TABLE sermons ADD FULLTEXT INDEX ft_sermons_search (title, content, introduction, conclusion)');
            } catch (\Exception $e) {
                // Index already exists or table doesn't support FULLTEXT
                \Log::warning('Could not add FULLTEXT index to sermons: ' . $e->getMessage());
            }
        }

        // إضافة Full-Text Index على جدول articles
        if (Schema::hasTable('articles')) {
            try {
                DB::statement('ALTER TABLE articles ADD FULLTEXT INDEX ft_articles_search (title, content, excerpt)');
            } catch (\Exception $e) {
                // Index already exists or table doesn't support FULLTEXT
                \Log::warning('Could not add FULLTEXT index to articles: ' . $e->getMessage());
            }
        }

        // إضافة Full-Text Index على جدول fatwas
        if (Schema::hasTable('fatwas')) {
            try {
                DB::statement('ALTER TABLE fatwas ADD FULLTEXT INDEX ft_fatwas_search (title, question, answer)');
            } catch (\Exception $e) {
                // Index already exists or table doesn't support FULLTEXT
                \Log::warning('Could not add FULLTEXT index to fatwas: ' . $e->getMessage());
            }
        }

        // إضافة Full-Text Index على جدول lectures
        if (Schema::hasTable('lectures')) {
            try {
                DB::statement('ALTER TABLE lectures ADD FULLTEXT INDEX ft_lectures_search (title, description, topic)');
            } catch (\Exception $e) {
                // Index already exists or table doesn't support FULLTEXT
                \Log::warning('Could not add FULLTEXT index to lectures: ' . $e->getMessage());
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف Full-Text Indexes
        if (Schema::hasTable('sermons')) {
            try {
                DB::statement('ALTER TABLE sermons DROP INDEX ft_sermons_search');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('articles')) {
            try {
                DB::statement('ALTER TABLE articles DROP INDEX ft_articles_search');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('fatwas')) {
            try {
                DB::statement('ALTER TABLE fatwas DROP INDEX ft_fatwas_search');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }

        if (Schema::hasTable('lectures')) {
            try {
                DB::statement('ALTER TABLE lectures DROP INDEX ft_lectures_search');
            } catch (\Exception $e) {
                // Index doesn't exist
            }
        }
    }
};

