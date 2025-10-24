<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreacherMiddleware
{
    /**
     * Handle an incoming request.
     * التحقق من أن المستخدم خطيب أو عالم
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // التحقق من تسجيل الدخول
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً للوصول لهذه الصفحة');
        }

        $user = Auth::user();

        // التحقق من أن الحساب نشط
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }

        // التحقق من أن المستخدم خطيب أو عالم أو مفكر أو مدخل بيانات
        if (!in_array($user->user_type, ['preacher', 'scholar', 'thinker', 'data_entry', 'admin'])) {
            return response()->view('errors.403', [
                'message' => 'هذه الصفحة مخصصة للخطباء والعلماء والمفكرين ومدخلي البيانات فقط. نوع حسابك الحالي: ' . ($user->user_type ?? 'غير محدد')
            ], 403);
        }

        return $next($request);
    }
}
