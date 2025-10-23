<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class ThinkerController extends Controller
{
    /**
     * عرض قائمة المفكرين والدعاة
     */
    public function index()
    {
        // المفكرون المميزون
        $featuredThinkers = User::where('is_active', true)
            ->where('user_type', 'thinker')
            ->orderBy('name', 'asc')
            ->take(6)
            ->get();

        // أحدث المقالات
        $articles = Article::with(['author', 'category'])
            ->where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // إحصائيات التصنيفات
        $islamicThoughtCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%فكر%')->orWhere('name', 'like', '%إسلامي%');
            })
            ->count();

        $dawahCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%دعوة%')->orWhere('name', 'like', '%إرشاد%');
            })
            ->count();

        $educationCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%تربية%')->orWhere('name', 'like', '%تعليم%');
            })
            ->count();

        $societyCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%مجتمع%')->orWhere('name', 'like', '%أسرة%');
            })
            ->count();

        $youthCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%شباب%');
            })
            ->count();

        $contemporaryCount = Article::where('is_published', true)
            ->whereHas('category', function($q) {
                $q->where('name', 'like', '%معاصر%')->orWhere('name', 'like', '%قضايا%');
            })
            ->count();

        return view('thinkers.index-unified', compact(
            'featuredThinkers',
            'articles',
            'islamicThoughtCount',
            'dawahCount',
            'educationCount',
            'societyCount',
            'youthCount',
            'contemporaryCount'
        ));
    }

    /**
     * عرض ملف مفكر محدد
     */
    public function show($id)
    {
        $thinker = User::where('is_active', true)
            ->findOrFail($id);

        // مقالات المفكر (إذا كانت متوفرة)
        $articles = collect(); // مجموعة فارغة مؤقتاً

        // إحصائيات المفكر
        $stats = [
            'articles_count' => 0,
            'total_views' => 0,
            'total_likes' => 0,
        ];

        return view('thinkers.show', compact('thinker', 'articles', 'stats'));
    }
}
