@extends('layouts.app')

@section('title', 'الفتاوى')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="page-header text-center mb-5">
        <h1 class="mb-3" style="color: #1d8a4e;">
            <i class="fas fa-balance-scale me-2"></i>
            الفتاوى الشرعية
        </h1>
        <p class="lead text-muted">تصفح الفتاوى الشرعية من علمائنا الأفاضل</p>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-balance-scale"></i>
                <div>
                    <h3 style="color: #1d8a4e;">{{ number_format($stats['total']) }}</h3>
                    <p class="text-muted mb-0">إجمالي الفتاوى</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="stat-card">
                <i class="fas fa-tags"></i>
                <div>
                    <h3 style="color: #1d8a4e;">{{ number_format($stats['categories']) }}</h3>
                    <p class="text-muted mb-0">التصنيفات</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Fatwas List -->
    @if($fatwas->count() > 0)
        <div class="row">
            @foreach($fatwas as $fatwa)
                <div class="col-md-6 mb-4">
                    <div class="fatwa-card">
                        <div class="fatwa-header">
                            <span class="fatwa-category">{{ $fatwa->category }}</span>
                            <span class="fatwa-date">
                                <i class="fas fa-calendar"></i>
                                {{ $fatwa->published_at ? $fatwa->published_at->format('d/m/Y') : $fatwa->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <h3 class="fatwa-title">
                            <a href="{{ route('fatwas.show', $fatwa->id) }}">{{ $fatwa->title }}</a>
                        </h3>
                        <div class="fatwa-scholar mb-2">
                            <i class="fas fa-user-graduate"></i>
                            <a href="{{ route('fatwas.scholar', $fatwa->scholar_id) }}">{{ $fatwa->scholar->name }}</a>
                        </div>
                        <div class="fatwa-question">
                            <strong>السؤال:</strong>
                            <p>{{ Str::limit(strip_tags($fatwa->question), 120) }}</p>
                        </div>
                        <div class="fatwa-footer">
                            <span class="fatwa-views">
                                <i class="fas fa-eye"></i>
                                {{ number_format($fatwa->views_count ?? 0) }}
                            </span>
                            <a href="{{ route('fatwas.show', $fatwa->id) }}" class="btn btn-sm btn-success">
                                اقرأ المزيد
                                <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($fatwas->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $fatwas->links() }}
            </div>
        @endif
    @else
        <div class="empty-state text-center py-5">
            <i class="fas fa-info-circle mb-3" style="font-size: 4rem; color: #ccc;"></i>
            <h3 class="text-muted">لا توجد فتاوى متاحة حالياً</h3>
            <p class="text-muted">لم يتم نشر أي فتاوى بعد</p>
            
            <div class="mt-4">
                @auth
                    <a href="{{ route('questions.ask') }}" class="btn btn-primary">
                        <i class="fas fa-question-circle me-2"></i>
                        اطرح سؤالاً
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        سجل دخولك لطرح سؤال
                    </a>
                @endauth
            </div>
        </div>
    @endif

    <!-- Ask Question Section -->
    <div class="ask-question-section mt-5 p-4 text-center" style="background: linear-gradient(135deg, #1d8a4e, #0f7346); border-radius: 10px; color: white;">
        <h3 class="mb-3">
            <i class="fas fa-question-circle me-2"></i>
            هل لديك سؤال؟
        </h3>
        <p class="mb-4">اطرح سؤالك وسيجيب عليه أحد علمائنا الأفاضل</p>
        @auth
            <a href="{{ route('questions.ask') }}" class="btn btn-light btn-lg">
                <i class="fas fa-pen me-2"></i>
                اطرح سؤالك الآن
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>
                سجل دخولك لطرح سؤال
            </a>
        @endauth
    </div>
</div>

<style>
.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-card i {
    font-size: 2.5rem;
    color: #1d8a4e;
}

.stat-card h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: bold;
}

.fatwa-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 100%;
    transition: all 0.3s ease;
}

.fatwa-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    transform: translateY(-5px);
}

.fatwa-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.fatwa-category {
    background: #1d8a4e;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
}

.fatwa-date {
    color: #666;
    font-size: 0.9rem;
}

.fatwa-title {
    font-size: 1.3rem;
    margin-bottom: 10px;
}

.fatwa-title a {
    color: #1d8a4e;
    text-decoration: none;
    font-weight: bold;
}

.fatwa-title a:hover {
    color: #0f7346;
    text-decoration: underline;
}

.fatwa-scholar {
    color: #666;
    font-size: 0.95rem;
}

.fatwa-scholar a {
    color: #1d8a4e;
    text-decoration: none;
}

.fatwa-scholar a:hover {
    text-decoration: underline;
}

.fatwa-question {
    margin-bottom: 15px;
    color: #555;
}

.fatwa-question strong {
    color: #333;
}

.fatwa-question p {
    margin: 5px 0 0 0;
    line-height: 1.6;
}

.fatwa-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.fatwa-views {
    color: #666;
}
</style>
@endsection

