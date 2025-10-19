<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الأدوار
        $adminRole = Role::create(['name' => 'admin']);
        $scholarRole = Role::create(['name' => 'scholar']);
        $memberRole = Role::create(['name' => 'member']);
        $guestRole = Role::create(['name' => 'guest']);

        // إنشاء الصلاحيات
        $permissions = [
            'create_sermons',
            'edit_sermons',
            'delete_sermons',
            'publish_sermons',
            'create_fatwas',
            'edit_fatwas',
            'delete_fatwas',
            'manage_users',
            'manage_content',
            'view_admin_panel'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // تعيين الصلاحيات للأدوار
        $adminRole->givePermissionTo(Permission::all());
        $scholarRole->givePermissionTo([
            'create_sermons', 'edit_sermons', 'publish_sermons',
            'create_fatwas', 'edit_fatwas'
        ]);
        $memberRole->givePermissionTo(['create_sermons', 'edit_sermons']);

        // إنشاء المستخدمين التجريبيين
        $users = [
            [
                'name' => 'عبدالرحمن السريحي',
                'email' => 'admin@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'مدير منصة تمسيك الإسلامية',
                'specialization' => 'إدارة المحتوى الإسلامي',
                'location' => 'مأرب، اليمن',
                'phone' => '+967-1-234567',
                'is_active' => true,
                'role' => 'admin'
            ],
            [
                'name' => 'د. خالد الكبودي',
                'email' => 'kabody@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'دكتور في الشريعة الإسلامية، متخصص في الفقه والأصول',
                'specialization' => 'الفقه والأصول',
                'location' => 'صنعاء، اليمن',
                'phone' => '+967-1-345678',
                'is_active' => true,
                'role' => 'scholar'
            ],
            [
                'name' => 'د. عبدالرحمن باحنان',
                'email' => 'bahannan@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'باحث في العلوم الشرعية، متخصص في التفسير والحديث',
                'specialization' => 'التفسير والحديث',
                'location' => 'حضرموت، اليمن',
                'phone' => '+967-5-456789',
                'is_active' => true,
                'role' => 'scholar'
            ],
            [
                'name' => 'محمد الزبيدي',
                'email' => 'zubaidi@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'خطيب وإمام مسجد، متخصص في الخطابة والوعظ',
                'specialization' => 'الخطابة والوعظ',
                'location' => 'تعز، اليمن',
                'phone' => '+967-4-567890',
                'is_active' => true,
                'role' => 'member'
            ],
            [
                'name' => 'أحمد الحداد',
                'email' => 'haddad@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'طالب علم شرعي، مهتم بالدعوة والإرشاد',
                'specialization' => 'الدعوة والإرشاد',
                'location' => 'إب، اليمن',
                'phone' => '+967-4-678901',
                'is_active' => true,
                'role' => 'member'
            ],
            [
                'name' => 'الشيخ محمد صلاح',
                'email' => 'salah@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'عالم في علوم القرآن والتفسير',
                'specialization' => 'علوم القرآن والتفسير',
                'location' => 'ذمار، اليمن',
                'phone' => '+967-6-789012',
                'is_active' => true,
                'role' => 'scholar'
            ]
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);
            
            $user = User::create($userData);
            $user->assignRole($role);
        }
    }
}
