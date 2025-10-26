<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CheckProductionReadiness extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'التحقق من جاهزية المشروع للنشر - Check if the project is ready for production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('========================================');
        $this->info('🔍 فحص جاهزية المشروع للنشر');
        $this->info('Production Readiness Check');
        $this->info('========================================');
        $this->newLine();

        $issues = [];
        $warnings = [];
        $passed = 0;
        $total = 0;

        // 1. التحقق من APP_ENV
        $total++;
        $this->info('📋 1. التحقق من APP_ENV...');
        if (config('app.env') === 'production') {
            $this->line('   ✅ APP_ENV=production');
            $passed++;
        } else {
            $this->warn('   ⚠️  APP_ENV=' . config('app.env') . ' (يجب أن يكون production)');
            $warnings[] = 'APP_ENV ليس production';
        }

        // 2. التحقق من APP_DEBUG
        $total++;
        $this->info('📋 2. التحقق من APP_DEBUG...');
        if (config('app.debug') === false) {
            $this->line('   ✅ APP_DEBUG=false');
            $passed++;
        } else {
            $this->error('   ❌ APP_DEBUG=true (يجب أن يكون false في الإنتاج!)');
            $issues[] = 'APP_DEBUG لا زال true';
        }

        // 3. التحقق من APP_KEY
        $total++;
        $this->info('📋 3. التحقق من APP_KEY...');
        if (!empty(config('app.key'))) {
            $this->line('   ✅ APP_KEY موجود');
            $passed++;
        } else {
            $this->error('   ❌ APP_KEY غير موجود! (شغّل: php artisan key:generate)');
            $issues[] = 'APP_KEY غير موجود';
        }

        // 4. التحقق من اتصال قاعدة البيانات
        $total++;
        $this->info('📋 4. التحقق من قاعدة البيانات...');
        try {
            DB::connection()->getPdo();
            $this->line('   ✅ الاتصال بقاعدة البيانات ناجح');
            $passed++;
        } catch (\Exception $e) {
            $this->error('   ❌ فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
            $issues[] = 'فشل الاتصال بقاعدة البيانات';
        }

        // 5. التحقق من صلاحيات المجلدات
        $total++;
        $this->info('📋 5. التحقق من صلاحيات المجلدات...');
        $storageWritable = is_writable(storage_path());
        $bootstrapWritable = is_writable(base_path('bootstrap/cache'));
        
        if ($storageWritable && $bootstrapWritable) {
            $this->line('   ✅ صلاحيات المجلدات صحيحة');
            $passed++;
        } else {
            if (!$storageWritable) {
                $this->error('   ❌ مجلد storage غير قابل للكتابة');
                $issues[] = 'مجلد storage غير قابل للكتابة';
            }
            if (!$bootstrapWritable) {
                $this->error('   ❌ مجلد bootstrap/cache غير قابل للكتابة');
                $issues[] = 'مجلد bootstrap/cache غير قابل للكتابة';
            }
        }

        // 6. التحقق من Storage Link
        $total++;
        $this->info('📋 6. التحقق من Storage Link...');
        if (File::exists(public_path('storage'))) {
            $this->line('   ✅ Storage Link موجود');
            $passed++;
        } else {
            $this->warn('   ⚠️  Storage Link غير موجود (شغّل: php artisan storage:link)');
            $warnings[] = 'Storage Link غير موجود';
        }

        // 7. التحقق من HTTPS في الإنتاج
        $total++;
        $this->info('📋 7. التحقق من HTTPS...');
        $appUrl = config('app.url');
        if (str_starts_with($appUrl, 'https://')) {
            $this->line('   ✅ APP_URL يستخدم HTTPS');
            $passed++;
        } else {
            $this->warn('   ⚠️  APP_URL لا يستخدم HTTPS: ' . $appUrl);
            $warnings[] = 'APP_URL لا يستخدم HTTPS';
        }

        // 8. التحقق من تكوين البريد الإلكتروني
        $total++;
        $this->info('📋 8. التحقق من تكوين البريد الإلكتروني...');
        $mailHost = config('mail.mailers.smtp.host');
        $mailUsername = config('mail.mailers.smtp.username');
        
        if (!empty($mailHost) && !empty($mailUsername)) {
            $this->line('   ✅ البريد الإلكتروني مُكوّن');
            $passed++;
        } else {
            $this->warn('   ⚠️  البريد الإلكتروني غير مُكوّن بالكامل');
            $warnings[] = 'البريد الإلكتروني غير مُكوّن';
        }

        // 9. التحقق من ملفات Cache
        $total++;
        $this->info('📋 9. التحقق من Cache Optimization...');
        $configCached = File::exists(base_path('bootstrap/cache/config.php'));
        $routesCached = File::exists(base_path('bootstrap/cache/routes-v7.php'));
        
        if ($configCached && $routesCached) {
            $this->line('   ✅ Cache Optimization مفعّل');
            $passed++;
        } else {
            $this->warn('   ⚠️  Cache Optimization غير مفعّل (شغّل: php artisan optimize)');
            $warnings[] = 'Cache Optimization غير مفعّل';
        }

        // 10. التحقق من وجود جداول قاعدة البيانات
        $total++;
        $this->info('📋 10. التحقق من جداول قاعدة البيانات...');
        try {
            $tables = DB::select('SHOW TABLES');
            if (count($tables) > 0) {
                $this->line('   ✅ جداول قاعدة البيانات موجودة (' . count($tables) . ' جدول)');
                $passed++;
            } else {
                $this->error('   ❌ لا توجد جداول في قاعدة البيانات (شغّل: php artisan migrate)');
                $issues[] = 'لا توجد جداول في قاعدة البيانات';
            }
        } catch (\Exception $e) {
            $this->error('   ❌ خطأ في قراءة الجداول: ' . $e->getMessage());
            $issues[] = 'خطأ في قراءة جداول قاعدة البيانات';
        }

        // النتيجة النهائية
        $this->newLine();
        $this->info('========================================');
        $this->info('📊 النتيجة النهائية');
        $this->info('========================================');
        
        $percentage = ($passed / $total) * 100;
        $this->line("✅ اجتاز: $passed من $total (" . round($percentage, 1) . "%)");
        
        if (count($issues) > 0) {
            $this->newLine();
            $this->error('🔴 مشاكل حرجة يجب حلها:');
            foreach ($issues as $issue) {
                $this->error('   • ' . $issue);
            }
        }
        
        if (count($warnings) > 0) {
            $this->newLine();
            $this->warn('🟡 تحذيرات (يُنصح بحلها):');
            foreach ($warnings as $warning) {
                $this->warn('   • ' . $warning);
            }
        }

        $this->newLine();
        
        if (count($issues) === 0 && count($warnings) === 0) {
            $this->info('🎉 المشروع جاهز للنشر بنسبة 100%!');
            return Command::SUCCESS;
        } elseif (count($issues) === 0) {
            $this->info('✅ المشروع جاهز للنشر (مع بعض التحذيرات)');
            return Command::SUCCESS;
        } else {
            $this->error('❌ المشروع غير جاهز للنشر - يرجى حل المشاكل الحرجة أولاً');
            return Command::FAILURE;
        }
    }
}

