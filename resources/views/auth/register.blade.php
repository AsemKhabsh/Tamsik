@extends('layouts.app')

@section('title', 'إنشاء حساب جديد - تمسيك')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <div class="auth-logo mb-3">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h2 class="mb-0">إنشاء حساب جديد</h2>
                    <p class="mb-0 mt-2">انضم إلى منصة تمسيك الإسلامية</p>
                </div>
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    الاسم الكامل *
                                </label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    البريد الإلكتروني *
                                </label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    كلمة المرور *
                                </label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    تأكيد كلمة المرور *
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="user_type" class="form-label">
                                <i class="fas fa-user-tag me-1"></i>
                                نوع الحساب *
                            </label>
                            <select name="user_type" id="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                <option value="">اختر نوع الحساب</option>
                                <option value="member" {{ old('user_type') == 'member' ? 'selected' : '' }}>عضو عادي</option>
                                <option value="preacher" {{ old('user_type') == 'preacher' ? 'selected' : '' }}>خطيب</option>
                                <option value="scholar" {{ old('user_type') == 'scholar' ? 'selected' : '' }}>عالم</option>
                                <option value="thinker" {{ old('user_type') == 'thinker' ? 'selected' : '' }}>مفكر</option>
                                <option value="data_entry" {{ old('user_type') == 'data_entry' ? 'selected' : '' }}>مدخل بيانات</option>
                            </select>
                            @error('user_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <strong>ملاحظة:</strong> حسابات الخطباء والعلماء والمفكرين ومدخلي البيانات تحتاج موافقة الإدارة
                            </small>
                        </div>

                        <div id="approval-notice" class="alert alert-info d-none">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>تنبيه:</strong> سيتم إرسال طلبك للإدارة للمراجعة والموافقة. ستتلقى إشعاراً عبر البريد الإلكتروني عند تفعيل حسابك.
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    أوافق على <a href="#" class="text-primary">شروط الاستخدام</a> و <a href="#" class="text-primary">سياسة الخصوصية</a>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                إنشاء الحساب
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">
                            لديك حساب بالفعل؟
                            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold">
                                سجل دخولك هنا
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-logo {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 1.5rem;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(44, 85, 48, 0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('user_type');
    const approvalNotice = document.getElementById('approval-notice');

    userTypeSelect.addEventListener('change', function() {
        const selectedType = this.value;

        if (['preacher', 'scholar', 'thinker'].includes(selectedType)) {
            approvalNotice.classList.remove('d-none');
        } else {
            approvalNotice.classList.add('d-none');
        }
    });
});
</script>
@endsection
