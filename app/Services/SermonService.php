<?php

namespace App\Services;

use App\Models\Sermon;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SermonService
{
    /**
     * الحصول على جميع الخطب المنشورة مع pagination
     */
    public function getAllSermons($perPage = 12)
    {
        return Sermon::where('is_published', true)
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على خطبة واحدة
     */
    public function getSermonById($id)
    {
        return Sermon::where('is_published', true)
            ->with(['author', 'scholar'])
            ->findOrFail($id);
    }

    /**
     * الحصول على خطب عالم محدد
     */
    public function getSermonsByAuthor($authorId, $perPage = 10)
    {
        return Sermon::where('author_id', $authorId)
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * البحث في الخطب
     */
    public function searchSermons($query, $perPage = 20)
    {
        return Sermon::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('summary', 'like', "%{$query}%");
            })
            ->with('author')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على الخطب الأكثر مشاهدة
     */
    public function getPopularSermons($limit = 5)
    {
        return Cache::remember('popular_sermons', 3600, function() use ($limit) {
            return Sermon::where('is_published', true)
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على أحدث الخطب
     */
    public function getRecentSermons($limit = 5)
    {
        return Cache::remember('recent_sermons', 1800, function() use ($limit) {
            return Sermon::where('is_published', true)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews($sermonId)
    {
        $sermon = Sermon::find($sermonId);
        if ($sermon) {
            $sermon->increment('views_count');
            Cache::forget('popular_sermons');
        }
        return $sermon;
    }

    /**
     * زيادة عدد التحميلات
     */
    public function incrementDownloads($sermonId)
    {
        $sermon = Sermon::find($sermonId);
        if ($sermon) {
            $sermon->increment('downloads_count');
        }
        return $sermon;
    }

    /**
     * إنشاء خطبة جديدة
     */
    public function createSermon(array $data)
    {
        DB::beginTransaction();
        try {
            $sermon = Sermon::create($data);
            Cache::forget('recent_sermons');
            DB::commit();
            return $sermon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث خطبة
     */
    public function updateSermon($id, array $data)
    {
        DB::beginTransaction();
        try {
            $sermon = Sermon::findOrFail($id);
            $sermon->update($data);
            Cache::forget('recent_sermons');
            Cache::forget('popular_sermons');
            DB::commit();
            return $sermon;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * حذف خطبة
     */
    public function deleteSermon($id)
    {
        DB::beginTransaction();
        try {
            $sermon = Sermon::findOrFail($id);
            $sermon->delete();
            Cache::forget('recent_sermons');
            Cache::forget('popular_sermons');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الحصول على إحصائيات الخطب
     */
    public function getSermonStats()
    {
        return Cache::remember('sermon_stats', 3600, function() {
            return [
                'total' => Sermon::where('is_published', true)->count(),
                'total_views' => Sermon::where('is_published', true)->sum('views_count'),
                'total_downloads' => Sermon::where('is_published', true)->sum('downloads_count'),
                'authors_count' => Sermon::where('is_published', true)->distinct('author_id')->count('author_id'),
            ];
        });
    }

    /**
     * الحصول على خطب ذات صلة
     */
    public function getRelatedSermons($sermonId, $limit = 5)
    {
        $sermon = Sermon::find($sermonId);
        if (!$sermon) {
            return collect([]);
        }

        return Sermon::where('is_published', true)
            ->where('id', '!=', $sermonId)
            ->where('author_id', $sermon->author_id)
            ->with(['author', 'scholar'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

