<?php

namespace App\Http\Controllers;

use App\Models\Sermon;
use App\Models\Lecture;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * إضافة أو تحديث تقييم
     */
    public function store(Request $request, $type, $id)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        // التحقق من صحة البيانات
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000'
        ]);

        // تحديد النموذج بناءً على النوع
        $model = $this->getModel($type, $id);
        
        if (!$model) {
            return response()->json([
                'success' => false,
                'message' => 'العنصر غير موجود'
            ], 404);
        }

        // التحقق من وجود تقييم سابق
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('ratable_type', get_class($model))
            ->where('ratable_id', $model->id)
            ->first();

        if ($existingRating) {
            // تحديث التقييم الموجود
            $existingRating->update([
                'rating' => $request->rating,
                'review' => $request->review
            ]);
            
            $rating = $existingRating;
            $message = 'تم تحديث تقييمك بنجاح';
        } else {
            // إنشاء تقييم جديد
            $rating = Rating::create([
                'user_id' => Auth::id(),
                'ratable_type' => get_class($model),
                'ratable_id' => $model->id,
                'rating' => $request->rating,
                'review' => $request->review
            ]);
            
            $message = 'تم إضافة تقييمك بنجاح';
        }

        // حساب متوسط التقييم الجديد
        $averageRating = $model->ratings()->avg('rating');
        $ratingsCount = $model->ratings()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'rating' => $rating,
            'average_rating' => round($averageRating, 1),
            'ratings_count' => $ratingsCount
        ]);
    }

    /**
     * حذف تقييم
     */
    public function destroy($type, $id)
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

        // حذف التقييم
        $deleted = Rating::where('user_id', Auth::id())
            ->where('ratable_type', get_class($model))
            ->where('ratable_id', $model->id)
            ->delete();

        if ($deleted) {
            // حساب متوسط التقييم الجديد
            $averageRating = $model->ratings()->avg('rating');
            $ratingsCount = $model->ratings()->count();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف تقييمك بنجاح',
                'average_rating' => round($averageRating ?? 0, 1),
                'ratings_count' => $ratingsCount
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'لم يتم العثور على تقييم'
        ], 404);
    }

    /**
     * الحصول على تقييم المستخدم الحالي
     */
    public function getUserRating($type, $id)
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

        // الحصول على تقييم المستخدم
        $rating = Rating::where('user_id', Auth::id())
            ->where('ratable_type', get_class($model))
            ->where('ratable_id', $model->id)
            ->first();

        return response()->json([
            'success' => true,
            'rating' => $rating
        ]);
    }

    /**
     * الحصول على النموذج المناسب
     */
    private function getModel($type, $id)
    {
        switch ($type) {
            case 'sermon':
            case 'sermons':
                return Sermon::find($id);
            
            case 'lecture':
            case 'lectures':
                return Lecture::find($id);
            
            default:
                return null;
        }
    }
}

