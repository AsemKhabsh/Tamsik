<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PerformanceMonitor
{
    protected static $startTime;
    protected static $startMemory;
    protected static $checkpoints = [];

    /**
     * بدء مراقبة الأداء
     */
    public static function start()
    {
        self::$startTime = microtime(true);
        self::$startMemory = memory_get_usage();
        self::$checkpoints = [];
    }

    /**
     * إضافة نقطة فحص
     */
    public static function checkpoint($name)
    {
        if (!self::$startTime) {
            self::start();
        }

        self::$checkpoints[$name] = [
            'time' => microtime(true) - self::$startTime,
            'memory' => memory_get_usage() - self::$startMemory,
        ];
    }

    /**
     * إنهاء المراقبة والحصول على النتائج
     */
    public static function end()
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $results = [
            'total_time' => $endTime - self::$startTime,
            'total_memory' => $endMemory - self::$startMemory,
            'peak_memory' => memory_get_peak_usage(),
            'checkpoints' => self::$checkpoints,
        ];

        return $results;
    }

    /**
     * تسجيل نتائج الأداء
     */
    public static function log($context = '')
    {
        $results = self::end();
        
        Log::info('Performance Monitor: ' . $context, [
            'execution_time' => round($results['total_time'] * 1000, 2) . ' ms',
            'memory_used' => self::formatBytes($results['total_memory']),
            'peak_memory' => self::formatBytes($results['peak_memory']),
            'checkpoints' => $results['checkpoints'],
        ]);

        return $results;
    }

    /**
     * تنسيق البايتات
     */
    protected static function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * مراقبة استعلام قاعدة البيانات
     */
    public static function monitorQuery($callback, $name = 'Query')
    {
        $start = microtime(true);
        $result = $callback();
        $duration = microtime(true) - $start;

        if ($duration > 1) { // أكثر من ثانية
            Log::warning('Slow Query Detected: ' . $name, [
                'duration' => round($duration * 1000, 2) . ' ms',
            ]);
        }

        return $result;
    }

    /**
     * الحصول على إحصائيات الأداء من الكاش
     */
    public static function getStats($period = 'daily')
    {
        $key = "performance_stats_{$period}";
        return Cache::get($key, []);
    }

    /**
     * حفظ إحصائيات الأداء
     */
    public static function saveStats($data, $period = 'daily')
    {
        $key = "performance_stats_{$period}";
        $stats = Cache::get($key, []);
        
        $stats[] = array_merge($data, [
            'timestamp' => now()->toDateTimeString(),
        ]);

        // الاحتفاظ بآخر 100 سجل فقط
        if (count($stats) > 100) {
            $stats = array_slice($stats, -100);
        }

        Cache::put($key, $stats, now()->addDays(7));
    }

    /**
     * تحليل الأداء
     */
    public static function analyze()
    {
        $stats = self::getStats();
        
        if (empty($stats)) {
            return [
                'average_time' => 0,
                'average_memory' => 0,
                'slow_requests' => 0,
            ];
        }

        $totalTime = 0;
        $totalMemory = 0;
        $slowRequests = 0;

        foreach ($stats as $stat) {
            $totalTime += $stat['total_time'] ?? 0;
            $totalMemory += $stat['total_memory'] ?? 0;
            
            if (($stat['total_time'] ?? 0) > 1) {
                $slowRequests++;
            }
        }

        $count = count($stats);

        return [
            'average_time' => round($totalTime / $count * 1000, 2) . ' ms',
            'average_memory' => self::formatBytes($totalMemory / $count),
            'slow_requests' => $slowRequests,
            'total_requests' => $count,
        ];
    }

    /**
     * مسح الإحصائيات
     */
    public static function clearStats($period = 'daily')
    {
        $key = "performance_stats_{$period}";
        Cache::forget($key);
    }

    /**
     * تتبع الأداء تلقائياً
     */
    public static function track($name, $callback)
    {
        self::start();
        
        try {
            $result = $callback();
            
            $stats = self::end();
            self::saveStats(array_merge($stats, ['name' => $name]));
            
            // تسجيل إذا كان بطيئاً
            if ($stats['total_time'] > 1) {
                Log::warning('Slow Operation: ' . $name, [
                    'time' => round($stats['total_time'] * 1000, 2) . ' ms',
                    'memory' => self::formatBytes($stats['total_memory']),
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Performance Monitor Error: ' . $name, [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}

