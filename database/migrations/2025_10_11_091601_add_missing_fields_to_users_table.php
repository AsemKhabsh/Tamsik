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
        Schema::table('users', function (Blueprint $table) {
            // إضافة الأعمدة المفقودة فقط
            $table->enum('role', ['admin', 'scholar', 'member'])->default('member')->after('email');
            $table->string('title')->nullable()->after('name'); // اللقب العلمي
            $table->string('image')->nullable()->after('avatar'); // الصورة الشخصية (مختلف عن avatar)
            $table->string('education')->nullable()->after('specialization'); // المؤهل العلمي
            $table->timestamp('last_login_at')->nullable()->after('education'); // آخر دخول
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'title',
                'image',
                'education',
                'last_login_at'
            ]);
        });
    }
};
