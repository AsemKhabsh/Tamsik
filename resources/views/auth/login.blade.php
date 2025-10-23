@extends('layouts.app')

@section('title', 'تسجيل الدخول - تمسيك')

@push('styles')
<style>
        .login-page-wrapper {
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            min-height: calc(100vh - 120px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
        }
        
        .auth-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .auth-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .auth-title {
            color: #1d8a4e;
            font-size: 2rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .auth-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Amiri', serif;
            transition: border-color 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #1d8a4e;
        }
        
        .form-input.error {
            border-color: #dc3545;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .checkbox-input {
            width: 18px;
            height: 18px;
        }
        
        .checkbox-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .auth-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Amiri', serif;
        }
        
        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .auth-links {
            text-align: center;
            margin-top: 20px;
        }
        
        .auth-link {
            color: #1d8a4e;
            text-decoration: none;
            font-weight: bold;
        }
        
        .auth-link:hover {
            text-decoration: underline;
        }
        
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #666;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #ddd;
            z-index: 1;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            z-index: 2;
        }
        

</style>
@endpush

@section('content')
<div class="login-page-wrapper">
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-mosque"></i>
            </div>
            <h1 class="auth-title">تسجيل الدخول</h1>
            <p class="auth-subtitle">مرحباً بك في موقع تمسك</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                       placeholder="أدخل بريدك الإلكتروني"
                       required>
                @if($errors->has('email'))
                    <div class="error-message">{{ $errors->first('email') }}</div>
                @endif
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="أدخل كلمة المرور"
                       required>
                @if($errors->has('password'))
                    <div class="error-message">{{ $errors->first('password') }}</div>
                @endif
            </div>
            
            <div class="form-checkbox">
                <input type="checkbox" id="remember" name="remember" class="checkbox-input">
                <label for="remember" class="checkbox-label">تذكرني</label>
            </div>
            
            <button type="submit" class="auth-btn">
                <i class="fas fa-sign-in-alt"></i>
                تسجيل الدخول
            </button>
        </form>
        
        <div class="divider">
            <span>أو</span>
        </div>
        
        <div class="auth-links">
            <p>ليس لديك حساب؟ <a href="{{ route('register') }}" class="auth-link">إنشاء حساب جديد</a></p>
            <p><a href="#" class="auth-link">نسيت كلمة المرور؟</a></p>
        </div>
    </div>
@endsection
