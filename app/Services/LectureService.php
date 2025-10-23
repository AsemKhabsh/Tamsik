<?php

namespace App\Services;

use App\Models\Lecture;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LectureService
{
    /**
     * الحصول على جميع المحاضرات المنشورة
     */
    public function getAllLectures($perPage = 12)
    {
        return Lecture::where('is_published', true)
            ->with('speaker')
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على محاضرة واحدة
     */
    public function getLectureById($id)
    {
        return Lecture::where('is_published', true)
            ->with('speaker')
            ->findOrFail($id);
    }

    /**
     * الحصول على محاضرات محاضر محدد
     */
    public function getLecturesByLecturer($lecturerId, $perPage = 10)
    {
        return Lecture::where('speaker_id', $lecturerId)
            ->where('is_published', true)
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * البحث في المحاضرات
     */
    public function searchLectures($query, $perPage = 20)
    {
        return Lecture::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('topic', 'like', "%{$query}%");
            })
            ->with('speaker')
            ->orderBy('scheduled_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على المحاضرات الأكثر مشاهدة
     */
    public function getPopularLectures($limit = 5)
    {
        return Cache::remember('popular_lectures', 3600, function() use ($limit) {
            return Lecture::where('is_published', true)
                ->orderBy('views_count', 'desc')
                ->orderBy('registered_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على أحدث المحاضرات
     */
    public function getRecentLectures($limit = 5)
    {
        return Cache::remember('recent_lectures', 1800, function() use ($limit) {
            return Lecture::where('is_published', true)
                ->where('scheduled_at', '<', now())
                ->orderBy('scheduled_at', 'desc')
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
            ->orderBy('scheduled_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews($lectureId)
    {
        try {
            $lecture = Lecture::find($lectureId);
            if ($lecture) {
                $lecture->increment('views_count');
                Cache::forget("lecture_{$lectureId}");
                Cache::forget('lecture_stats');
                return $lecture;
            }
            return null;
        } catch (\Exception $e) {
            \Log::error('Error incrementing lecture views: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * إنشاء محاضرة جديدة
     */
    public function createLecture(array $data)
    {
        DB::beginTransaction();
        try {
            $lecture = Lecture::create($data);
            Cache::forget('recent_lectures');
            DB::commit();
            return $lecture;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث محاضرة
     */
    public function updateLecture($id, array $data)
    {
        DB::beginTransaction();
        try {
            $lecture = Lecture::findOrFail($id);
            $lecture->update($data);
            Cache::forget('recent_lectures');
            Cache::forget('popular_lectures');
            DB::commit();
            return $lecture;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * حذف محاضرة
     */
    public function deleteLecture($id)
    {
        DB::beginTransaction();
        try {
            $lecture = Lecture::findOrFail($id);
            $lecture->delete();
            Cache::forget('recent_lectures');
            Cache::forget('popular_lectures');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الحصول على إحصائيات المحاضرات
     */
    public function getLectureStats()
    {
        return Cache::remember('lecture_stats', 3600, function() {
            return [
                'total' => Lecture::where('is_published', true)->count(),
                'total_views' => Lecture::where('is_published', true)->sum('views_count'),
                'total_registered' => Lecture::where('is_published', true)->sum('registered_count'),
                'upcoming' => Lecture::where('is_published', true)->where('scheduled_at', '>', now())->count(),
                'speakers_count' => Lecture::where('is_published', true)->distinct('speaker_id')->count('speaker_id'),
            ];
        });
    }

    /**
     * الحصول على محاضرات ذات صلة
     */
    public function getRelatedLectures($lectureId, $limit = 5)
    {
        $lecture = Lecture::find($lectureId);
        if (!$lecture) {
            return collect([]);
        }

        return Lecture::where('is_published', true)
            ->where('id', '!=', $lectureId)
            ->where('speaker_id', $lecture->speaker_id)
            ->orderBy('scheduled_at', 'desc')
            ->limit($limit)
            ->get();
    }
}

