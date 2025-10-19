@extends('layouts.app')

@section('title', 'غير مصرح - 403')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5">
                    <div class="error-icon mb-4">
                        <i class="fas fa-lock text-danger" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h1 class="display-4 text-danger mb-3">403</h1>
                    <h2 class="h4 mb-4">غير مصرح بالوصول</h2>
                    
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ $message ?? 'ليس لديك صلاحية للوصول إلى هذه الصفحة' }}
                    </div>
                    
                    <p class="text-muted mb-4">
                        إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع الإدارة أو التأكد من نوع حسابك.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            العودة للرئيسية
                        </a>

                        @auth
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-user-edit me-2"></i>
                                الملف الشخصي
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                تسجيل الدخول
                            </a>
                        @endauth
                    </div>
                    
                    @auth
                        <div class="mt-4 p-3 bg-light rounded">
                            <small class="text-muted">
                                <strong>معلومات الحساب:</strong><br>
                                الاسم: {{ auth()->user()->name }}<br>
                                البريد الإلكتروني: {{ auth()->user()->email }}<br>
                                نوع الحساب: {{ auth()->user()->user_type ?? 'غير محدد' }}
                            </small>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}
</style>
@endsection
