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
            AdminSeeder::class,
            UserSeeder::class,
            SermonSeeder::class,
            LectureSeeder::class,
            ArticleSeeder::class,
            // يمكن إضافة المزيد من الـ Seeders هنا لاحقاً
        ]);
    }
}
