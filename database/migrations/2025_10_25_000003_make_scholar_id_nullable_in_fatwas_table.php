<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * جعل حقل scholar_id nullable لأن الأسئلة الجديدة قد لا يتم تعيين عالم لها مباشرة
     */
    public function up(): void
    {
        // حذف foreign key constraint أولاً
        Schema::table('fatwas', function (Blueprint $table) {
            $table->dropForeign(['scholar_id']);
        });

        // تعديل العمود ليكون nullable
        Schema::table('fatwas', function (Blueprint $table) {
            $table->foreignId('scholar_id')->nullable()->change();
        });

        // إعادة إضافة foreign key constraint
        Schema::table('fatwas', function (Blueprint $table) {
            $table->foreign('scholar_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف foreign key constraint
        Schema::table('fatwas', function (Blueprint $table) {
            $table->dropForeign(['scholar_id']);
        });

        // تعديل العمود ليكون NOT NULL
        Schema::table('fatwas', function (Blueprint $table) {
            $table->foreignId('scholar_id')->nullable(false)->change();
        });

        // إعادة إضافة foreign key constraint
        Schema::table('fatwas', function (Blueprint $table) {
            $table->foreign('scholar_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};

