<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Favorite;
use Illuminate\Support\Facades\DB;

class CleanInvalidFavorites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'favorites:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تنظيف المفضلات التي تحتوي على class غير موجود أو عناصر محذوفة';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 جاري البحث عن المفضلات التالفة...');

        $favorites = Favorite::all();
        $deletedCount = 0;
        $totalCount = $favorites->count();

        $this->info("📊 إجمالي المفضلات: {$totalCount}");

        $progressBar = $this->output->createProgressBar($totalCount);
        $progressBar->start();

        foreach ($favorites as $favorite) {
            $shouldDelete = false;
            $reason = '';

            // التحقق من وجود الـ class
            if (!class_exists($favorite->favoritable_type)) {
                $shouldDelete = true;
                $reason = "Class غير موجود: {$favorite->favoritable_type}";
            } else {
                // التحقق من وجود العنصر في قاعدة البيانات
                try {
                    $model = $favorite->favoritable_type;
                    $item = $model::find($favorite->favoritable_id);
                    
                    if (!$item) {
                        $shouldDelete = true;
                        $reason = "العنصر محذوف (ID: {$favorite->favoritable_id})";
                    }
                } catch (\Exception $e) {
                    $shouldDelete = true;
                    $reason = "خطأ في تحميل العنصر: " . $e->getMessage();
                }
            }

            if ($shouldDelete) {
                $this->newLine();
                $this->warn("❌ حذف مفضلة (ID: {$favorite->id}) - {$reason}");
                $favorite->delete();
                $deletedCount++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        if ($deletedCount > 0) {
            $this->info("✅ تم حذف {$deletedCount} مفضلة تالفة من أصل {$totalCount}");
        } else {
            $this->info("✅ لا توجد مفضلات تالفة. جميع المفضلات صحيحة!");
        }

        // إحصائيات بعد التنظيف
        $remainingCount = Favorite::count();
        $this->info("📊 المفضلات المتبقية: {$remainingCount}");

        return Command::SUCCESS;
    }
}

