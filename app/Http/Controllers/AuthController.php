<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Authentication Controller
 * 
 * معالج طلبات تسجيل الدخول والتسجيل وتسجيل الخروج
 */
class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Redirect based on user role
            if (Auth::user()->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'مرحباً بك في لوحة الإدارة');
            }

            // Redirect regular users to home
            return redirect()->intended(route('home'))
                ->with('success', 'تم تسجيل الدخول بنجاح');
        }

        // Authentication failed
        return back()->withErrors([
            'email' => 'البيانات المدخلة غير صحيحة.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|string|in:member,preacher,scholar,thinker,data_entry',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب ألا يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'user_type.required' => 'نوع المستخدم مطلوب',
            'user_type.in' => 'نوع المستخدم غير صحيح',
            'terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
        ]);

        $userType = $validated['user_type'];
        $needsApproval = in_array($userType, ['preacher', 'scholar', 'thinker', 'data_entry']);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $userType,
            'is_active' => !$needsApproval,
        ]);

        // Assign role using Spatie
        if ($needsApproval) {
            $user->assignRole('guest');
        } else {
            $user->assignRole($userType);
        }

        // Handle approval workflow
        if ($needsApproval) {
            // TODO: Send notification to admin
            // Mail::to('admin@tamsik.org')->send(new NewUserApprovalRequest($user));

            return redirect()->route('register')->with('warning',
                'تم إنشاء حسابك بنجاح! سيتم مراجعة طلبك من قبل الإدارة وستتلقى إشعاراً عبر البريد الإلكتروني عند تفعيل حسابك.');
        }

        // Auto-login for approved users
        Auth::login($user);
        
        return redirect()->route('home')
            ->with('success', 'مرحباً بك في منصة تمسيك!');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        
        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'تم تسجيل الخروج بنجاح');
    }

    /**
     * Quick admin login (for local development only)
     * 
     * @deprecated This should only be used in local environment
     */
    public function quickAdminLogin(Request $request)
    {
        // Only allow in local environment
        if (!app()->environment('local')) {
            abort(404);
        }

        $admin = User::where('email', 'admin@tamsik.com')->first();
        
        if ($admin) {
            Auth::login($admin, true); // remember = true
            $request->session()->regenerate();
            
            return redirect()->route('admin.dashboard')
                ->with('success', 'تم تسجيل الدخول كمدير');
        }

        return redirect()->route('login')
            ->with('error', 'لم يتم العثور على حساب المدير');
    }
}

