@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<style>
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #1d8a4e;
    }
    .profile-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .profile-header {
        background: linear-gradient(135deg, #1d8a4e 0%, #15693a 100%);
        color: white;
        padding: 20px;
        border-radius: 10px 10px 0 0;
    }
    .stat-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 10px;
    }
    .stat-card h4 {
        color: #1d8a4e;
        font-size: 24px;
        margin-bottom: 5px;
    }
    .stat-card p {
        color: #666;
        margin: 0;
        font-size: 14px;
    }
    .nav-tabs .nav-link {
        color: #666;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs .nav-link.active {
        color: #1d8a4e;
        border-bottom: 3px solid #1d8a4e;
        background: transparent;
    }
    .nav-tabs .nav-link {
        cursor: pointer;
    }
    .tab-content {
        padding-top: 20px;
    }
    .tab-content {
        display: block !important;
        width: 100%;
        min-height: 300px;
    }
    .tab-pane {
        display: none !important;
    }
    .tab-pane.active {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        min-height: 200px;
        width: 100%;
    }
</style>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- الشريط الجانبي -->
        <div class="col-md-4 mb-4">
            <div class="card profile-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="profile-avatar">
                        @else
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        @endif
                    </div>
                    <h5 class="card-title mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-2">{{ $user->email }}</p>
                    <span class="badge bg-primary mb-3">{{ $user->getRoleName() }}</span>

                    @if($user->bio)
                        <p class="text-muted small">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="card profile-card mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>الإحصائيات</h6>

                    @if($stats['sermons_count'] > 0)
                    <div class="stat-card">
                        <h4>{{ $stats['sermons_count'] }}</h4>
                        <p>خطبة</p>
                    </div>
                    @endif

                    @if($stats['articles_count'] > 0)
                    <div class="stat-card">
                        <h4>{{ $stats['articles_count'] }}</h4>
                        <p>مقالة</p>
                    </div>
                    @endif

                    @if($stats['lectures_count'] > 0)
                    <div class="stat-card">
                        <h4>{{ $stats['lectures_count'] }}</h4>
                        <p>محاضرة</p>
                    </div>
                    @endif

                    @if($stats['fatwas_count'] > 0)
                    <div class="stat-card">
                        <h4>{{ $stats['fatwas_count'] }}</h4>
                        <p>فتوى</p>
                    </div>
                    @endif

                    <div class="stat-card">
                        <h4>{{ $stats['favorites_count'] }}</h4>
                        <p>مفضلة</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="col-md-8">
            <div class="card profile-card">
                <div class="profile-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        إدارة الحساب
                    </h4>
                </div>
                <div class="card-body">
                    <!-- التبويبات -->
                    <ul class="nav nav-tabs mb-4" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#profile-info" onclick="switchTab(event, 'profile-info')">
                                <i class="fas fa-user me-2"></i>المعلومات الشخصية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#change-password" onclick="switchTab(event, 'change-password')">
                                <i class="fas fa-lock me-2"></i>تغيير كلمة المرور
                            </a>
                        </li>
                    </ul>

                    <!-- محتوى التبويبات -->
                    <div class="tab-content">
                        <!-- تبويب المعلومات الشخصية -->
                        <div class="tab-pane show active" id="profile-info">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الاسم <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">رقم الهاتف</label>
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الموقع</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $user->location) }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">التخصص</label>
                                    <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $user->specialization) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">نبذة تعريفية</label>
                                    <textarea name="bio" class="form-control" rows="4">{{ old('bio', $user->bio) }}</textarea>
                                    <small class="text-muted">حد أقصى 1000 حرف</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">الصورة الشخصية</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                    <small class="text-muted">الصيغ المدعومة: JPG, PNG, GIF (حد أقصى 2MB)</small>

                                    @if($user->avatar)
                                        <div class="mt-2">
                                            <form action="{{ route('profile.delete-avatar') }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من حذف الصورة الشخصية؟')">
                                                    <i class="fas fa-trash me-1"></i>حذف الصورة الحالية
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">نوع الحساب</label>
                                    <input type="text" class="form-control" value="{{ $user->getRoleName() }}" readonly disabled>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">حالة الحساب</label>
                                    <input type="text" class="form-control" value="{{ $user->is_active ? 'نشط' : 'معلق' }}" readonly disabled>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- تبويب تغيير كلمة المرور -->
                        <div class="tab-pane" id="change-password">
                            <form action="{{ route('profile.change-password') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الحالية <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الجديدة <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                    <small class="text-muted">يجب أن تكون 8 أحرف على الأقل</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تأكيد كلمة المرور الجديدة <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    سيتم تسجيل خروجك من جميع الأجهزة الأخرى بعد تغيير كلمة المرور
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-key me-2"></i>تغيير كلمة المرور
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Debug on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded');
    var profileInfo = document.getElementById('profile-info');
    console.log('Profile info element:', profileInfo);
    console.log('Profile info classes:', profileInfo ? profileInfo.className : 'NOT FOUND');
    console.log('Profile info display:', profileInfo ? window.getComputedStyle(profileInfo).display : 'NOT FOUND');
});

// Simple tab switching function
function switchTab(event, tabId) {
    event.preventDefault();
    console.log('Switching to tab:', tabId);

    // Remove active class from all tabs
    var tabs = document.querySelectorAll('.nav-tabs .nav-link');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });

    // Remove active and show from all tab panes
    var panes = document.querySelectorAll('.tab-pane');
    panes.forEach(function(pane) {
        pane.classList.remove('show', 'active');
    });

    // Add active class to clicked tab
    event.currentTarget.classList.add('active');

    // Show the selected tab pane
    var targetPane = document.getElementById(tabId);
    console.log('Target pane:', targetPane);
    if (targetPane) {
        targetPane.classList.add('show', 'active');
        console.log('Tab switched successfully');
    } else {
        console.error('Tab pane not found:', tabId);
    }
}

// Preview image before upload
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.querySelector('input[name="avatar"]');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarImg = document.querySelector('.profile-avatar img');
                    if (avatarImg) {
                        avatarImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
