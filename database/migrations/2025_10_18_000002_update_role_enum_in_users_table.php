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
        // تعديل عمود role لإضافة القيم الجديدة
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'scholar', 'preacher', 'thinker', 'data_entry', 'member') DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع العمود إلى القيم القديمة
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'scholar', 'member') DEFAULT 'member'");
    }
};

