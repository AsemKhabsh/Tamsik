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

        // تنظيف المفضلات التي تحتوي على class غير موجود
        $this->cleanInvalidFavorites($user);

        // جلب جميع المفضلات
        $favoritesQuery = $user->favorites()->latest();

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

        // تحميل العلاقات بشكل آمن
        $favorites->getCollection()->transform(function ($favorite) {
            try {
                if (class_exists($favorite->favoritable_type)) {
                    $favorite->load('favoritable');
                }
            } catch (\Exception $e) {
                // تجاهل الأخطاء وحذف المفضلة التالفة
                $favorite->delete();
                return null;
            }
            return $favorite;
        })->filter(); // إزالة العناصر null

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

        // التحقق من أن الـ Model موجود
        if (!class_exists($model)) {
            return response()->json([
                'success' => false,
                'message' => 'نوع العنصر غير صحيح'
            ], 400);
        }

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

        // Log للتأكد من البيانات المستلمة
        \Log::info('Favorite Toggle Request', [
            'user_id' => $user->id,
            'favoritable_type' => $validated['favoritable_type'],
            'favoritable_id' => $validated['favoritable_id'],
            'class_exists' => class_exists($validated['favoritable_type'])
        ]);

        // التحقق من أن الـ Model موجود
        $model = $validated['favoritable_type'];
        if (!class_exists($model)) {
            \Log::error('Class does not exist', ['class' => $model]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع العنصر غير صحيح'
                ], 400);
            }
            return back()->with('error', 'نوع العنصر غير صحيح');
        }

        // التحقق من وجود العنصر
        $item = $model::find($validated['favoritable_id']);
        if (!$item) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'العنصر غير موجود'
                ], 404);
            }
            return back()->with('error', 'العنصر غير موجود');
        }

        $favorite = $user->favorites()
            ->where('favoritable_type', $validated['favoritable_type'])
            ->where('favoritable_id', $validated['favoritable_id'])
            ->first();

        if ($favorite) {
            // إزالة من المفضلات
            $favorite->delete();
            $message = 'تمت الإزالة من المفضلات بنجاح';
            $isFavorited = false;
            \Log::info('Favorite removed', ['favorite_id' => $favorite->id]);
        } else {
            // إضافة للمفضلات
            $newFavorite = $user->favorites()->create([
                'favoritable_type' => $validated['favoritable_type'],
                'favoritable_id' => $validated['favoritable_id'],
            ]);
            $message = 'تمت الإضافة للمفضلات بنجاح';
            $isFavorited = true;
            \Log::info('Favorite added', [
                'favorite_id' => $newFavorite->id,
                'stored_type' => $newFavorite->favoritable_type
            ]);
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

    /**
     * تنظيف المفضلات التي تحتوي على class غير موجود
     */
    private function cleanInvalidFavorites($user)
    {
        $favorites = $user->favorites()->get();

        foreach ($favorites as $favorite) {
            // التحقق من وجود الـ class
            if (!class_exists($favorite->favoritable_type)) {
                $favorite->delete();
                continue;
            }

            // التحقق من وجود العنصر في قاعدة البيانات
            try {
                $model = $favorite->favoritable_type;
                $item = $model::find($favorite->favoritable_id);

                if (!$item) {
                    // العنصر محذوف، احذف المفضلة
                    $favorite->delete();
                }
            } catch (\Exception $e) {
                // خطأ في تحميل العنصر، احذف المفضلة
                $favorite->delete();
            }
        }
    }
}
