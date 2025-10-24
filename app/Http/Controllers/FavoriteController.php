<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Sermon;
use App\Models\Article;
use App\Models\Fatwa;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * عرض صفحة المفضلات
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all'); // all, sermons, articles, fatwas, lectures

        // جلب جميع المفضلات
        $favoritesQuery = $user->favorites()->with('favoritable')->latest();

        // تصفية حسب النوع
        if ($type !== 'all') {
            $typeMap = [
                'sermons' => Sermon::class,
                'articles' => Article::class,
                'fatwas' => Fatwa::class,
                'lectures' => Lecture::class,
            ];

            if (isset($typeMap[$type])) {
                $favoritesQuery->where('favoritable_type', $typeMap[$type]);
            }
        }

        $favorites = $favoritesQuery->paginate(12);

        // إحصائيات المفضلات
        $stats = [
            'total' => $user->favorites()->count(),
            'sermons' => $user->favorites()->where('favoritable_type', Sermon::class)->count(),
            'articles' => $user->favorites()->where('favoritable_type', Article::class)->count(),
            'fatwas' => $user->favorites()->where('favoritable_type', Fatwa::class)->count(),
            'lectures' => $user->favorites()->where('favoritable_type', Lecture::class)->count(),
        ];

        return view('favorites.index', compact('favorites', 'stats', 'type'));
    }

    /**
     * إضافة عنصر للمفضلات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'favoritable_type' => 'required|string',
            'favoritable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        // التحقق من وجود العنصر
        $model = $validated['favoritable_type'];
        $item = $model::find($validated['favoritable_id']);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'العنصر غير موجود'
            ], 404);
        }

        // التحقق من عدم وجود المفضلة مسبقاً
        $exists = $user->favorites()
            ->where('favoritable_type', $validated['favoritable_type'])
            ->where('favoritable_id', $validated['favoritable_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'العنصر موجود بالفعل في المفضلات'
            ], 400);
        }

        // إضافة للمفضلات
        $favorite = $user->favorites()->create([
            'favoritable_type' => $validated['favoritable_type'],
            'favoritable_id' => $validated['favoritable_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تمت الإضافة للمفضلات بنجاح',
            'favorite' => $favorite
        ]);
    }

    /**
     * إزالة عنصر من المفضلات
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'favoritable_type' => 'required|string',
            'favoritable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $favorite = $user->favorites()
            ->where('favoritable_type', $validated['favoritable_type'])
            ->where('favoritable_id', $validated['favoritable_id'])
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'العنصر غير موجود في المفضلات'
            ], 404);
        }

        $favorite->delete();

        return response()->json([
            'success' => true,
            'message' => 'تمت الإزالة من المفضلات بنجاح'
        ]);
    }

    /**
     * تبديل حالة المفضلة (إضافة/إزالة)
     */
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'favoritable_type' => 'required|string',
            'favoritable_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $favorite = $user->favorites()
            ->where('favoritable_type', $validated['favoritable_type'])
            ->where('favoritable_id', $validated['favoritable_id'])
            ->first();

        if ($favorite) {
            // إزالة من المفضلات
            $favorite->delete();
            $message = 'تمت الإزالة من المفضلات بنجاح';
            $isFavorited = false;
        } else {
            // إضافة للمفضلات
            $user->favorites()->create([
                'favoritable_type' => $validated['favoritable_type'],
                'favoritable_id' => $validated['favoritable_id'],
            ]);
            $message = 'تمت الإضافة للمفضلات بنجاح';
            $isFavorited = true;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorited' => $isFavorited
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * حذف جميع المفضلات
     */
    public function clear()
    {
        $user = Auth::user();
        $user->favorites()->delete();

        return redirect()->route('favorites')->with('success', 'تم حذف جميع المفضلات بنجاح');
    }
}

