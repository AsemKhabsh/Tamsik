<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * تبديل الإعجاب على مقال
     */
    public function toggle($articleId)
    {
        $article = Article::findOrFail($articleId);
        
        $isLiked = $article->toggleLike(Auth::user());
        
        return response()->json([
            'success' => true,
            'is_liked' => $isLiked,
            'likes_count' => $article->likes_count
        ]);
    }
}

