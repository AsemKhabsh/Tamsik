<?php

namespace App\Services;

use App\Models\User;
use App\Models\Sermon;
use App\Models\Fatwa;
use Illuminate\Support\Facades\Cache;

class ScholarService
{
    /**
     * الحصول على جميع العلماء النشطين
     */
    public function getAllScholars($perPage = 12)
    {
        return User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * الحصول على عالم واحد
     */
    public function getScholarById($id)
    {
        return User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->findOrFail($id);
    }

    /**
     * الحصول على خطب العالم
     */
    public function getScholarSermons($scholarId, $perPage = 10)
    {
        return Sermon::where('author_id', $scholarId)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على فتاوى العالم
     */
    public function getScholarFatwas($scholarId, $perPage = 10)
    {
        return Fatwa::where('scholar_id', $scholarId)
            ->where('is_published', true)
            ->whereNotNull('answer')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على إحصائيات العالم
     */
    public function getScholarStats($scholarId)
    {
        return Cache::remember("scholar_stats_{$scholarId}", 3600, function() use ($scholarId) {
            return [
                'sermons_count' => Sermon::where('author_id', $scholarId)
                    ->where('is_published', true)
                    ->count(),
                'fatwas_count' => Fatwa::where('scholar_id', $scholarId)
                    ->where('is_published', true)
                    ->whereNotNull('answer')
                    ->count(),
                'total_views' => Sermon::where('author_id', $scholarId)
                    ->where('is_published', true)
                    ->sum('views_count'),
                'total_downloads' => Sermon::where('author_id', $scholarId)
                    ->where('is_published', true)
                    ->sum('downloads_count'),
            ];
        });
    }

    /**
     * البحث في العلماء
     */
    public function searchScholars($query, $perPage = 12)
    {
        return User::where('user_type', 'scholar')
            ->where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('bio', 'like', "%{$query}%")
                  ->orWhere('specialization', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    /**
     * الحصول على العلماء الأكثر نشاطاً
     */
    public function getMostActiveScholars($limit = 5)
    {
        return Cache::remember('most_active_scholars', 3600, function() use ($limit) {
            return User::where('user_type', 'scholar')
                ->where('is_active', true)
                ->withCount(['sermons' => function($q) {
                    $q->where('is_published', true);
                }])
                ->orderBy('sermons_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على إحصائيات عامة للعلماء
     */
    public function getGeneralStats()
    {
        return Cache::remember('scholars_general_stats', 3600, function() {
            return [
                'total_scholars' => User::where('user_type', 'scholar')
                    ->where('is_active', true)
                    ->count(),
                'total_sermons' => Sermon::where('is_published', true)->count(),
                'total_fatwas' => Fatwa::where('is_published', true)
                    ->whereNotNull('answer')
                    ->count(),
            ];
        });
    }
}

