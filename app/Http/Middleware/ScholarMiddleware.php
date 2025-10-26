<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Scholar Middleware
 * 
 * التحقق من أن المستخدم عالم أو مدير
 * 
 * هذا الـ Middleware للصلاحيات الخاصة بالعلماء فقط (مثل الفتاوى)
 * 
 * ملاحظة: العالم (scholar) لديه جميع صلاحيات الخطيب (preacher) + صلاحيات إضافية:
 * - إنشاء وإدارة الفتاوى ✅
 * - الإجابة على الأسئلة الشرعية ✅
 * - مراجعة محتوى الخطباء (اختياري) ✅
 * - جميع صلاحيات الخطيب (إنشاء خطب، محاضرات، مقالات) ✅
 */
class ScholarMiddleware
{
    /**
     * Handle an incoming request.
     * التحقق من أن المستخدم عالم أو مدير
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

        // التحقق من أن المستخدم عالم أو مدير فقط
        // ملاحظة: هذا للصلاحيات الخاصة بالعلماء (مثل الفتاوى)
        $allowedRoles = ['admin', 'scholar'];

        if (!$user->hasAnyRole($allowedRoles)) {
            return response()->view('errors.403', [
                'message' => 'هذه الصفحة مخصصة للعلماء والمديرين فقط. دورك الحالي: ' . ($user->getRoleName() ?? 'غير محدد')
            ], 403);
        }

        return $next($request);
    }
}

