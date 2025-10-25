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
        $preacherRole = Role::create(['name' => 'preacher']);
        $thinkerRole = Role::create(['name' => 'thinker']);
        $dataEntryRole = Role::create(['name' => 'data_entry']);
        $memberRole = Role::create(['name' => 'member']);
        $guestRole = Role::create(['name' => 'guest']);

        // إنشاء الصلاحيات
        $permissions = [
            // صلاحيات الخطب
            'create_sermons',
            'edit_sermons',
            'delete_sermons',
            'publish_sermons',

            // صلاحيات المحاضرات
            'create_lectures',
            'edit_lectures',
            'delete_lectures',
            'publish_lectures',

            // صلاحيات الفتاوى
            'create_fatwas',
            'edit_fatwas',
            'delete_fatwas',
            'publish_fatwas',

            // صلاحيات المقالات
            'create_articles',
            'edit_articles',
            'delete_articles',
            'publish_articles',

            // صلاحيات التعليقات
            'comment_on_articles',
            'reply_to_comments',
            'moderate_comments',

            // صلاحيات التفاعل
            'view_content',
            'like_content',
            'rate_content',
            'ask_scholars',

            // صلاحيات الإدارة
            'manage_users',
            'manage_content',
            'view_admin_panel'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // تعيين الصلاحيات للأدوار

        // 1. Admin - جميع الصلاحيات (24)
        $adminRole->givePermissionTo(Permission::all());

        // 2. Scholar - 9 صلاحيات
        $scholarRole->givePermissionTo([
            'create_sermons', 'edit_sermons', 'publish_sermons',
            'create_lectures', 'edit_lectures', 'publish_lectures',
            'create_fatwas', 'edit_fatwas', 'publish_fatwas',
            'view_content', 'like_content', 'rate_content', 'ask_scholars'
        ]);

        // 3. Preacher - 6 صلاحيات
        $preacherRole->givePermissionTo([
            'create_sermons', 'edit_sermons', 'publish_sermons',
            'create_lectures', 'edit_lectures', 'publish_lectures',
            'view_content', 'like_content', 'rate_content', 'ask_scholars'
        ]);

        // 4. Thinker - 6 صلاحيات
        $thinkerRole->givePermissionTo([
            'create_articles', 'edit_articles', 'publish_articles',
            'comment_on_articles', 'reply_to_comments', 'moderate_comments',
            'view_content', 'like_content', 'rate_content', 'ask_scholars'
        ]);

        // 5. Data Entry - 4 صلاحيات
        $dataEntryRole->givePermissionTo([
            'create_sermons',
            'create_lectures',
            'create_fatwas',
            'create_articles',
            'view_content'
        ]);

        // 6. Member - 5 صلاحيات
        $memberRole->givePermissionTo([
            'view_content',
            'like_content',
            'rate_content',
            'comment_on_articles',
            'ask_scholars'
        ]);

        // 7. Guest - 1 صلاحية
        $guestRole->givePermissionTo([
            'view_content'
        ]);

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
                'user_type' => 'member', // التصنيف الوظيفي
                'is_active' => true,
                'role' => 'admin' // دور Spatie
            ],
            [
                'name' => 'د. خالد الكبودي',
                'email' => 'kabody@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'دكتور في الشريعة الإسلامية، متخصص في الفقه والأصول',
                'specialization' => 'الفقه والأصول',
                'location' => 'صنعاء، اليمن',
                'phone' => '+967-1-345678',
                'user_type' => 'scholar',
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
                'user_type' => 'scholar',
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
                'user_type' => 'preacher',
                'is_active' => true,
                'role' => 'preacher'
            ],
            [
                'name' => 'أحمد الحداد',
                'email' => 'haddad@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'طالب علم شرعي، مهتم بالدعوة والإرشاد',
                'specialization' => 'الدعوة والإرشاد',
                'location' => 'إب، اليمن',
                'phone' => '+967-4-678901',
                'user_type' => 'member',
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
                'user_type' => 'scholar',
                'is_active' => true,
                'role' => 'scholar'
            ],
            [
                'name' => 'فاطمة الحكيمي',
                'email' => 'hakimi@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'مفكرة إسلامية، متخصصة في قضايا المرأة المسلمة',
                'specialization' => 'قضايا المرأة والأسرة',
                'location' => 'صنعاء، اليمن',
                'phone' => '+967-1-890123',
                'user_type' => 'thinker',
                'is_active' => true,
                'role' => 'thinker'
            ],
            [
                'name' => 'سعيد المحمدي',
                'email' => 'data@tamsik.com',
                'password' => Hash::make('123456'),
                'bio' => 'موظف إدخال بيانات في المنصة',
                'specialization' => 'إدخال البيانات',
                'location' => 'عدن، اليمن',
                'phone' => '+967-2-901234',
                'user_type' => 'data_entry',
                'is_active' => true,
                'role' => 'data_entry'
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
