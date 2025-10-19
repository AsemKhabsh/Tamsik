<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware
 * 
 * يضيف Security Headers للحماية من:
 * - XSS (Cross-Site Scripting)
 * - Clickjacking
 * - MIME Sniffing
 * - Information Disclosure
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // منع MIME Type Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // منع Clickjacking - السماح فقط بنفس الموقع
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // تفعيل XSS Protection في المتصفحات القديمة
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // التحكم في Referrer Information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // التحكم في Permissions (Geolocation, Camera, Microphone)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Content Security Policy - حماية شاملة من XSS
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com",
            "img-src 'self' data: https: blob:",
            "font-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com",
            "connect-src 'self'",
            "media-src 'self' blob:",
            "object-src 'none'",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'"
        ];
        
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // Strict Transport Security - إجبار HTTPS (فقط في Production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}

