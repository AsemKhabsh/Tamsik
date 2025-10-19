@extends('layouts.app')

@section('title', 'المفضلات')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-heart me-2"></i>
                        المفضلات
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <i class="fas fa-heart fa-5x text-muted mb-4"></i>
                    <h3 class="text-muted mb-3">لا توجد مفضلات بعد</h3>
                    <p class="lead">يمكنك إضافة الخطب والمحاضرات والمقالات المفضلة لديك هنا</p>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        نظام المفضلات قيد التطوير
                    </div>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-home me-2"></i>
                        العودة للصفحة الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

