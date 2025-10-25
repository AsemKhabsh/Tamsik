<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // يحتوي على جميع المستخدمين بما فيهم المدير
            SermonSeeder::class,
            LectureSeeder::class,
            ArticleSeeder::class,
            // يمكن إضافة المزيد من الـ Seeders هنا لاحقاً
        ]);
    }
}
