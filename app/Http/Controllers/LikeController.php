<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Sermon;
use App\Models\Fatwa;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * تبديل الإعجاب - يدعم جميع الأنواع
     */
    public function toggle(Request $request, $type, $id)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        // تحديد النموذج بناءً على النوع
        $model = $this->getModel($type, $id);

        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'العنصر غير موجود'
            ], 404);
        }

        // تبديل الإعجاب
        $isLiked = $model->toggleLike(Auth::user());

        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $model->likes_count
        ]);
    }

    /**
     * الحصول على النموذج المناسب
     */
    private function getModel($type, $id)
    {
        switch ($type) {
            case 'article':
            case 'articles':
                return Article::find($id);

            case 'sermon':
            case 'sermons':
                return Sermon::find($id);

            case 'fatwa':
            case 'fatwas':
                return Fatwa::find($id);

            case 'lecture':
            case 'lectures':
                return Lecture::find($id);

            default:
                return null;
        }
    }
}

