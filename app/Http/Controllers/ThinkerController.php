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
        $thinkers = User::where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(12);

        // استخدام Article model للمقالات - مع التحقق من وجود البيانات
        try {
            $articles = Article::published()->recent()->with('author')->take(10)->get();
        } catch (\Exception $e) {
            // في حالة عدم وجود مقالات، إنشاء مجموعة فارغة
            $articles = collect();
        }

        $featuredThinkers = $thinkers->take(6);

        // إحصائيات التصنيفات
        $islamicThoughtCount = 0;
        $dawahCount = 0;
        $educationCount = 0;
        $societyCount = 0;
        $youthCount = 0;
        $contemporaryCount = 0;

        // إحصائيات عامة
        $totalViews = 0;
        $totalComments = 0;

        return view('thinkers.index-unified', compact(
            'articles',
            'featuredThinkers',
            'islamicThoughtCount',
            'dawahCount',
            'educationCount',
            'societyCount',
            'youthCount',
            'contemporaryCount',
            'totalViews',
            'totalComments'
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
