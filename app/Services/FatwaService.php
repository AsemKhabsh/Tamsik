<?php

namespace App\Services;

use App\Models\Fatwa;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FatwaService
{
    /**
     * الحصول على جميع الفتاوى المنشورة
     */
    public function getAllFatwas($perPage = 12)
    {
        return Fatwa::where('is_published', true)
            ->whereNotNull('answer')
            ->with('scholar')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على فتوى واحدة
     */
    public function getFatwaById($id)
    {
        return Fatwa::where('id', $id)
            ->where('is_published', true)
            ->whereNotNull('answer')
            ->with('scholar')
            ->firstOrFail();
    }

    /**
     * الحصول على فتاوى عالم محدد
     */
    public function getFatwasByScholar($scholarId, $perPage = 12)
    {
        return Fatwa::where('scholar_id', $scholarId)
            ->where('is_published', true)
            ->whereNotNull('answer')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على فتاوى حسب التصنيف
     */
    public function getFatwasByCategory($category, $perPage = 12)
    {
        return Fatwa::where('category', $category)
            ->where('is_published', true)
            ->whereNotNull('answer')
            ->with('scholar')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * البحث في الفتاوى
     */
    public function searchFatwas($query, $perPage = 20)
    {
        return Fatwa::where('is_published', true)
            ->whereNotNull('answer')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('question', 'like', "%{$query}%")
                  ->orWhere('answer', 'like', "%{$query}%");
            })
            ->with('scholar')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على الفتاوى الأكثر مشاهدة
     */
    public function getPopularFatwas($limit = 5)
    {
        return Cache::remember('popular_fatwas', 3600, function() use ($limit) {
            return Fatwa::where('is_published', true)
                ->whereNotNull('answer')
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على أحدث الفتاوى
     */
    public function getRecentFatwas($limit = 5)
    {
        return Cache::remember('recent_fatwas', 1800, function() use ($limit) {
            return Fatwa::where('is_published', true)
                ->whereNotNull('answer')
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على الفتاوى غير المجابة
     */
    public function getUnansweredFatwas($perPage = 20)
    {
        return Fatwa::whereNull('answer')
            ->orWhere('answer', '')
            ->with('scholar')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews($fatwaId)
    {
        $fatwa = Fatwa::find($fatwaId);
        if ($fatwa) {
            $fatwa->increment('views_count');
            Cache::forget('popular_fatwas');
        }
        return $fatwa;
    }

    /**
     * إنشاء سؤال جديد
     */
    public function createQuestion(array $data)
    {
        DB::beginTransaction();
        try {
            $fatwa = Fatwa::create(array_merge($data, [
                'is_published' => false,
                'answer' => null,
            ]));
            DB::commit();
            return $fatwa;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الإجابة على سؤال
     */
    public function answerQuestion($id, $answer, $scholarId = null)
    {
        DB::beginTransaction();
        try {
            $fatwa = Fatwa::findOrFail($id);
            $fatwa->update([
                'answer' => $answer,
                'scholar_id' => $scholarId ?: $fatwa->scholar_id,
                'is_published' => true,
                'published_at' => now(),
            ]);
            Cache::forget('recent_fatwas');
            DB::commit();
            return $fatwa;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث فتوى
     */
    public function updateFatwa($id, array $data)
    {
        DB::beginTransaction();
        try {
            $fatwa = Fatwa::findOrFail($id);
            $fatwa->update($data);
            Cache::forget('recent_fatwas');
            Cache::forget('popular_fatwas');
            DB::commit();
            return $fatwa;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * حذف فتوى
     */
    public function deleteFatwa($id)
    {
        DB::beginTransaction();
        try {
            $fatwa = Fatwa::findOrFail($id);
            $fatwa->delete();
            Cache::forget('recent_fatwas');
            Cache::forget('popular_fatwas');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الحصول على إحصائيات الفتاوى
     */
    public function getFatwaStats()
    {
        return Cache::remember('fatwa_stats', 3600, function() {
            return [
                'total' => Fatwa::where('is_published', true)->whereNotNull('answer')->count(),
                'unanswered' => Fatwa::whereNull('answer')->orWhere('answer', '')->count(),
                'total_views' => Fatwa::where('is_published', true)->sum('views_count'),
                'scholars_count' => Fatwa::where('is_published', true)->distinct('scholar_id')->count('scholar_id'),
                'categories' => Fatwa::where('is_published', true)->whereNotNull('answer')->distinct('category')->count('category'),
            ];
        });
    }

    /**
     * الحصول على فتاوى ذات صلة
     */
    public function getRelatedFatwas($fatwaId, $limit = 5)
    {
        $fatwa = Fatwa::find($fatwaId);
        if (!$fatwa) {
            return collect([]);
        }

        return Fatwa::where('is_published', true)
            ->whereNotNull('answer')
            ->where('id', '!=', $fatwaId)
            ->where(function($q) use ($fatwa) {
                $q->where('category', $fatwa->category)
                  ->orWhere('scholar_id', $fatwa->scholar_id);
            })
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * الحصول على أسئلة المستخدم
     */
    public function getUserQuestions($userId, $perPage = 10)
    {
        return Fatwa::where('questioner_id', $userId)
            ->with('scholar')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على أسئلة العالم (الموجهة له)
     */
    public function getScholarQuestions($scholarId, $status = 'all', $perPage = 20)
    {
        $query = Fatwa::where('scholar_id', $scholarId)
            ->with(['questioner']);

        if ($status === 'pending') {
            $query->where('is_published', false)
                  ->where(function($q) {
                      $q->whereNull('answer')->orWhere('answer', '');
                  });
        } elseif ($status === 'answered') {
            $query->where('is_published', true)
                  ->whereNotNull('answer')
                  ->where('answer', '!=', '');
        } elseif ($status === 'draft') {
            $query->where('is_published', false)
                  ->whereNotNull('answer')
                  ->where('answer', '!=', '');
        }

        return $query->orderBy('created_at', 'desc')
                     ->paginate($perPage);
    }

    /**
     * الحصول على إحصائيات أسئلة العالم
     */
    public function getScholarQuestionsStats($scholarId)
    {
        $total = Fatwa::where('scholar_id', $scholarId)->count();

        $pending = Fatwa::where('scholar_id', $scholarId)
            ->where('is_published', false)
            ->where(function($q) {
                $q->whereNull('answer')->orWhere('answer', '');
            })
            ->count();

        $answered = Fatwa::where('scholar_id', $scholarId)
            ->where('is_published', true)
            ->whereNotNull('answer')
            ->where('answer', '!=', '')
            ->count();

        $draft = Fatwa::where('scholar_id', $scholarId)
            ->where('is_published', false)
            ->whereNotNull('answer')
            ->where('answer', '!=', '')
            ->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'answered' => $answered,
            'draft' => $draft,
        ];
    }

    /**
     * الحصول على سؤال واحد للعالم
     */
    public function getScholarQuestion($scholarId, $questionId)
    {
        return Fatwa::where('id', $questionId)
            ->where('scholar_id', $scholarId)
            ->with(['questioner', 'scholar'])
            ->firstOrFail();
    }
}

