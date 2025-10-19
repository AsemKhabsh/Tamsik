@extends('layouts.app')

@section('title', 'مقالاتي - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-2">
                <i class="fas fa-newspaper me-2 text-primary"></i>
                مقالاتي
            </h1>
            <p class="text-muted">إدارة مقالاتك المنشورة والمسودات</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>
                إضافة مقال جديد
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Articles List -->
    @if($articles->count() > 0)
        <div class="row">
            @foreach($articles as $article)
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm hover-lift">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $article->title }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <!-- Status Badge -->
                            <div class="mb-2">
                                @if($article->status === 'published')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        منشور
                                    </span>
                                @elseif($article->status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>
                                        قيد المراجعة
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-file me-1"></i>
                                        مسودة
                                    </span>
                                @endif

                                @if($article->is_featured)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star me-1"></i>
                                        مميز
                                    </span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h5 class="card-title">{{ $article->title }}</h5>

                            <!-- Excerpt -->
                            @if($article->excerpt)
                                <p class="card-text text-muted small">
                                    {{ Str::limit($article->excerpt, 100) }}
                                </p>
                            @endif

                            <!-- Meta Info -->
                            <div class="d-flex justify-content-between align-items-center mb-3 small text-muted">
                                <span>
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $article->created_at->format('Y/m/d') }}
                                </span>
                                <span>
                                    <i class="fas fa-eye me-1"></i>
                                    {{ $article->views_count ?? 0 }} مشاهدة
                                </span>
                                <span>
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $article->reading_time ?? 5 }} دقائق
                                </span>
                            </div>

                            <!-- Tags -->
                            @if($article->tags && count($article->tags) > 0)
                                <div class="mb-3">
                                    @foreach(array_slice($article->tags, 0, 3) as $tag)
                                        <span class="badge bg-light text-dark me-1">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('articles.edit', $article->id) }}" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-edit me-1"></i>
                                    تعديل
                                </a>
                                <form action="{{ route('articles.destroy', $article->id) }}" 
                                      method="POST" 
                                      class="flex-fill"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-trash me-1"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $articles->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-file-alt fa-5x text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">لا توجد مقالات بعد</h3>
            <p class="text-muted mb-4">ابدأ بكتابة مقالك الأول وشارك أفكارك مع القراء</p>
            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>
                إضافة مقال جديد
            </a>
        </div>
    @endif
</div>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
</style>
@endsection

