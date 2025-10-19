<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Http\Requests\StoreArticleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * عرض مقال للزوار
     */
    public function show($id)
    {
        $article = Article::with(['author', 'category', 'comments.user'])->findOrFail($id);

        // زيادة عدد المشاهدات
        $article->increment('views_count');

        // المقالات ذات الصلة
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where(function($query) use ($article) {
                if ($article->category_id) {
                    $query->where('category_id', $article->category_id);
                }
            })
            ->latest()
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    /**
     * عرض صفحة إنشاء مقال جديد
     */
    public function create()
    {
        // التحقق من الصلاحيات
        if (!in_array(Auth::user()->user_type, ['admin', 'scholar', 'thinker', 'data_entry'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بإضافة مقالات');
        }

        $categories = Category::all();
        return view('articles.create', compact('categories'));
    }

    /**
     * حفظ مقال جديد
     */
    public function store(StoreArticleRequest $request)
    {
        // الـ Authorization والـ Validation تلقائي من StoreArticleRequest
        $article = new Article();
        $article->title = $request->title;
        $article->slug = Str::slug($request->title);
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;
        $article->author_id = Auth::id();
        $article->category_id = $request->category_id;
        $article->meta_title = $request->meta_title ?? $request->title;
        $article->meta_description = $request->meta_description ?? $request->excerpt;
        
        // معالجة العلامات (tags)
        if ($request->tags) {
            $article->tags = array_map('trim', explode(',', $request->tags));
        }

        // حساب وقت القراءة (تقريبي: 200 كلمة في الدقيقة)
        $wordCount = str_word_count(strip_tags($request->content));
        $article->reading_time = max(1, ceil($wordCount / 200));

        // رفع الصورة المميزة
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('articles/images', 'public');
            $article->featured_image = $imagePath;
        }

        // تحديد حالة النشر حسب دور المستخدم
        if (in_array(Auth::user()->user_type, ['admin'])) {
            $article->status = 'published';
            $article->published_at = now();
        } else {
            $article->status = 'pending'; // يحتاج موافقة
        }

        $article->save();

        return redirect()->route('articles.my')->with('success', 'تم إنشاء المقال بنجاح! ' . 
            ($article->status === 'pending' ? 'سيتم مراجعته من قبل الإدارة قبل النشر.' : ''));
    }

    /**
     * عرض مقالات المستخدم الحالي
     */
    public function myArticles()
    {
        $user = Auth::user();

        if (!in_array($user->user_type, ['admin', 'scholar', 'thinker', 'data_entry'])) {
            return redirect()->route('home')->with('error', 'غير مصرح لك بالوصول لهذه الصفحة');
        }

        $articles = Article::where('author_id', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return view('articles.my-articles', compact('articles', 'user'));
    }

    /**
     * عرض صفحة تعديل مقال
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        // التحقق من الصلاحيات
        if ($article->author_id !== Auth::id() && Auth::user()->user_type !== 'admin') {
            return redirect()->route('articles.my')->with('error', 'غير مصرح لك بتعديل هذا المقال');
        }

        $categories = Category::all();
        return view('articles.edit', compact('article', 'categories'));
    }

    /**
     * تحديث مقال
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // التحقق من الصلاحيات
        if ($article->author_id !== Auth::id() && Auth::user()->user_type !== 'admin') {
            return redirect()->route('articles.my')->with('error', 'غير مصرح لك بتعديل هذا المقال');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $article->title = $request->title;
        $article->slug = Str::slug($request->title);
        $article->excerpt = $request->excerpt;
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        $article->meta_title = $request->meta_title ?? $request->title;
        $article->meta_description = $request->meta_description ?? $request->excerpt;
        
        // معالجة العلامات (tags)
        if ($request->tags) {
            $article->tags = array_map('trim', explode(',', $request->tags));
        }

        // حساب وقت القراءة
        $wordCount = str_word_count(strip_tags($request->content));
        $article->reading_time = max(1, ceil($wordCount / 200));

        // رفع الصورة المميزة
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('articles/images', 'public');
            $article->featured_image = $imagePath;
        }

        $article->save();

        return redirect()->route('articles.my')->with('success', 'تم تحديث المقال بنجاح!');
    }

    /**
     * حذف مقال
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // التحقق من الصلاحيات
        if ($article->author_id !== Auth::id() && Auth::user()->user_type !== 'admin') {
            return redirect()->route('articles.my')->with('error', 'غير مصرح لك بحذف هذا المقال');
        }

        $article->delete();

        return redirect()->route('articles.my')->with('success', 'تم حذف المقال بنجاح!');
    }
}

