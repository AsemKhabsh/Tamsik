<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * جعل حقل answer nullable لأن الأسئلة الجديدة لا تحتوي على إجابة
     */
    public function up(): void
    {
        Schema::table('fatwas', function (Blueprint $table) {
            $table->longText('answer')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fatwas', function (Blueprint $table) {
            $table->longText('answer')->nullable(false)->change();
        });
    }
};

