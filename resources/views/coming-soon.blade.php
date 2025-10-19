@extends('layouts.app')

@section('title', $title . ' - قريباً')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow-sm">
                <div class="card-body py-5">
                    <i class="fas fa-clock fa-5x text-primary mb-4"></i>
                    <h1 class="display-4 mb-3">{{ $title }}</h1>
                    <h3 class="text-muted mb-4">قريباً</h3>
                    <p class="lead">نعمل حالياً على تطوير هذا القسم. سيكون متاحاً قريباً إن شاء الله.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-4">
                        <i class="fas fa-home me-2"></i>
                        العودة للصفحة الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

