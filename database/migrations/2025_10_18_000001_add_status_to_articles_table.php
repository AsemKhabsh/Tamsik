<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // إضافة عمود status
            $table->enum('status', ['draft', 'pending', 'published'])->default('pending')->after('content');
            
            // إضافة عمود likes_count
            $table->integer('likes_count')->default(0)->after('views_count');
            
            // إضافة عمود category_id
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->onDelete('set null');
            
            // إضافة أعمدة SEO
            $table->string('meta_title')->nullable()->after('tags');
            $table->text('meta_description')->nullable()->after('meta_title');
        });

        // تحديث البيانات الموجودة: تحويل is_published إلى status
        DB::table('articles')->update([
            'status' => DB::raw("CASE WHEN is_published = 1 THEN 'published' ELSE 'draft' END")
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['status', 'likes_count', 'category_id', 'meta_title', 'meta_description']);
        });
    }
};

