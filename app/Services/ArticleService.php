<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ArticleService
{
    /**
     * الحصول على جميع المقالات المنشورة
     */
    public function getAllArticles($perPage = 12)
    {
        return Article::where('is_published', true)
            ->with('author', 'category')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على مقالة واحدة
     */
    public function getArticleById($id)
    {
        return Article::where('is_published', true)
            ->with('author', 'category', 'comments.user')
            ->findOrFail($id);
    }

    /**
     * الحصول على مقالات كاتب محدد
     */
    public function getArticlesByAuthor($authorId, $perPage = 10)
    {
        return Article::where('author_id', $authorId)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على مقالات تصنيف محدد
     */
    public function getArticlesByCategory($categoryId, $perPage = 12)
    {
        return Article::where('category_id', $categoryId)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * البحث في المقالات
     */
    public function searchArticles($query, $perPage = 20)
    {
        return Article::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with('author', 'category')
            ->orderBy('published_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * الحصول على المقالات الأكثر قراءة
     */
    public function getPopularArticles($limit = 5)
    {
        return Cache::remember('popular_articles', 3600, function() use ($limit) {
            return Article::where('is_published', true)
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * الحصول على أحدث المقالات
     */
    public function getRecentArticles($limit = 5)
    {
        return Cache::remember('recent_articles', 1800, function() use ($limit) {
            return Article::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews($articleId)
    {
        $article = Article::find($articleId);
        if ($article) {
            $article->increment('views_count');
            Cache::forget('popular_articles');
        }
        return $article;
    }

    /**
     * إنشاء مقالة جديدة
     */
    public function createArticle(array $data)
    {
        DB::beginTransaction();
        try {
            $article = Article::create($data);
            Cache::forget('recent_articles');
            DB::commit();
            return $article;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث مقالة
     */
    public function updateArticle($id, array $data)
    {
        DB::beginTransaction();
        try {
            $article = Article::findOrFail($id);
            $article->update($data);
            Cache::forget('recent_articles');
            Cache::forget('popular_articles');
            DB::commit();
            return $article;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * حذف مقالة
     */
    public function deleteArticle($id)
    {
        DB::beginTransaction();
        try {
            $article = Article::findOrFail($id);
            $article->delete();
            Cache::forget('recent_articles');
            Cache::forget('popular_articles');
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الحصول على إحصائيات المقالات
     */
    public function getArticleStats()
    {
        return Cache::remember('article_stats', 3600, function() {
            return [
                'total' => Article::where('is_published', true)->count(),
                'total_views' => Article::where('is_published', true)->sum('views_count'),
                'total_likes' => Article::where('is_published', true)->sum('likes_count'),
                'authors_count' => Article::where('is_published', true)->distinct('author_id')->count('author_id'),
            ];
        });
    }

    /**
     * الحصول على مقالات ذات صلة
     */
    public function getRelatedArticles($articleId, $limit = 5)
    {
        $article = Article::find($articleId);
        if (!$article) {
            return collect([]);
        }

        return Article::where('is_published', true)
            ->where('id', '!=', $articleId)
            ->where(function($q) use ($article) {
                $q->where('category_id', $article->category_id)
                  ->orWhere('author_id', $article->author_id);
            })
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * تبديل الإعجاب
     */
    public function toggleLike($articleId, $userId)
    {
        $article = Article::findOrFail($articleId);
        return $article->toggleLike(\App\Models\User::find($userId));
    }
}

