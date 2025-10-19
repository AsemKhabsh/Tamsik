@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h5 class="card-title">{{ Auth::user()->name }}</h5>
                    <p class="text-muted">{{ Auth::user()->email }}</p>
                    <span class="badge bg-primary">{{ Auth::user()->user_type }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        معلومات الحساب
                    </h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">نوع الحساب</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->user_type }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">حالة الحساب</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->is_active ? 'نشط' : 'معلق' }}" readonly>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            صفحة تعديل الملف الشخصي قيد التطوير
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

