<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * حفظ تعليق جديد على مقال
     */
    public function store(Request $request, $articleId)
    {
        $request->validate([
            'content' => 'required|string|min:3|max:1000',
        ]);

        $article = Article::findOrFail($articleId);

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id();
        $comment->commentable_type = Article::class;
        $comment->commentable_id = $article->id;
        $comment->is_approved = true; // يمكن تغييره لـ false إذا أردت مراجعة التعليقات
        $comment->save();

        return redirect()->route('articles.show', $article->id)
            ->with('success', 'تم إضافة تعليقك بنجاح!');
    }

    /**
     * حذف تعليق
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        // التحقق من الصلاحيات
        if ($comment->user_id !== Auth::id() && Auth::user()->user_type !== 'admin') {
            return redirect()->back()->with('error', 'غير مصرح لك بحذف هذا التعليق');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'تم حذف التعليق بنجاح');
    }
}

