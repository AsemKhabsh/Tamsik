<?php

namespace App\Services;

use App\Models\Sermon;
use App\Models\Article;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeService
{
    /**
     * الحصول على إحصائيات الصفحة الرئيسية
     */
    public function getHomeStats()
    {
        return Cache::remember('home_stats', 1800, function() {
            return [
                'sermons' => Sermon::where('is_published', true)->count(),
                'lectures' => Lecture::where('is_published', true)->count(),
                'articles' => Article::where('is_published', true)->count(),
                'scholars' => User::where('user_type', 'scholar')
                    ->where('is_active', true)
                    ->count(),
            ];
        });
    }

    /**
     * الحصول على أحدث الخطب
     */
    public function getRecentSermons($limit = 6)
    {
        return Sermon::where('is_published', true)
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * الحصول على أحدث المحاضرات
     */
    public function getRecentLectures($limit = 6)
    {
        return Lecture::where('is_published', true)
            ->with('speaker')
            ->where('scheduled_at', '<', now())
            ->orderBy('scheduled_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * الحصول على أحدث المقالات
     */
    public function getRecentArticles($limit = 6)
    {
        return Article::where('is_published', true)
            ->with('author', 'category')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * الحصول على الخطب الأكثر مشاهدة
     */
    public function getPopularSermons($limit = 5)
    {
        return Cache::remember('home_popular_sermons', 3600, function() use ($limit) {
            return Sermon::where('is_published', true)
                ->with('author')
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على المحاضرات القادمة
     */
    public function getUpcomingLectures($limit = 5)
    {
        return Lecture::where('is_published', true)
            ->where('scheduled_at', '>', now())
            ->with('speaker')
            ->orderBy('scheduled_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * الحصول على العلماء المميزين
     */
    public function getFeaturedScholars($limit = 4)
    {
        return Cache::remember('featured_scholars', 3600, function() use ($limit) {
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
     * الحصول على محتوى مميز للصفحة الرئيسية
     */
    public function getFeaturedContent()
    {
        return [
            'featured_sermon' => Sermon::where('is_published', true)
                ->where('is_featured', true)
                ->with('author')
                ->orderBy('created_at', 'desc')
                ->first(),
            'featured_article' => Article::where('is_published', true)
                ->where('is_featured', true)
                ->with('author', 'category')
                ->orderBy('published_at', 'desc')
                ->first(),
            'featured_lecture' => Lecture::where('is_published', true)
                ->where('is_featured', true)
                ->with('speaker')
                ->orderBy('scheduled_at', 'desc')
                ->first(),
        ];
    }

    /**
     * مسح الكاش
     */
    public function clearCache()
    {
        Cache::forget('home_stats');
        Cache::forget('home_popular_sermons');
        Cache::forget('featured_scholars');
    }
}

