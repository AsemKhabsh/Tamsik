<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء حساب المدير الرئيسي
        User::create([
            'name' => 'مدير الموقع',
            'email' => 'admin@tamsik.com',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // إنشاء حساب مدير ثانوي
        User::create([
            'name' => 'أسامة خباش',
            'email' => 'asem@tamsik.com',
            'password' => Hash::make('asem123'),
            'user_type' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // إنشاء حساب عالم تجريبي
        User::create([
            'name' => 'الشيخ محمد الحكيم',
            'email' => 'scholar@tamsik.com',
            'password' => Hash::make('scholar123'),
            'user_type' => 'scholar',
            'title' => 'الشيخ الدكتور',
            'bio' => 'عالم متخصص في الفقه والعقيدة، حاصل على دكتوراه في الشريعة الإسلامية من جامعة الأزهر.',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // إنشاء حساب خطيب تجريبي
        User::create([
            'name' => 'الشيخ عبدالله الخطيب',
            'email' => 'preacher@tamsik.com',
            'password' => Hash::make('preacher123'),
            'user_type' => 'preacher',
            'title' => 'خطيب وإمام',
            'bio' => 'خطيب مسجد الهدى، متخصص في الخطابة والوعظ والإرشاد.',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // إنشاء حساب عضو عادي
        User::create([
            'name' => 'أحمد محمد',
            'email' => 'member@tamsik.com',
            'password' => Hash::make('member123'),
            'user_type' => 'member',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        echo "تم إنشاء الحسابات التالية:\n";
        echo "1. مدير الموقع - admin@tamsik.com - كلمة المرور: admin123\n";
        echo "2. أسامة خباش - asem@tamsik.com - كلمة المرور: asem123\n";
        echo "3. الشيخ محمد الحكيم - scholar@tamsik.com - كلمة المرور: scholar123\n";
        echo "4. الشيخ عبدالله الخطيب - preacher@tamsik.com - كلمة المرور: preacher123\n";
        echo "5. أحمد محمد - member@tamsik.com - كلمة المرور: member123\n";
    }
}
