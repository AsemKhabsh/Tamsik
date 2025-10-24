<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // التحقق من أن الحساب نشط
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }

        // التحقق من دور المدير
        if ($user->role !== 'admin') {
            abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
        }

        return $next($request);
    }
}
