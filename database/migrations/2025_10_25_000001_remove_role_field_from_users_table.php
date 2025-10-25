<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * حذف حقل role من جدول users والاعتماد على Spatie Permission فقط
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف حقل role - سنستخدم Spatie Permission بدلاً منه
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إعادة حقل role في حالة التراجع
            $table->enum('role', ['admin', 'scholar', 'preacher', 'thinker', 'data_entry', 'member'])
                  ->default('member')
                  ->after('user_type');
        });
    }
};

