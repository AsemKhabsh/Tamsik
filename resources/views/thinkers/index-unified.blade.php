@extends('layouts.app')

@section('title', 'المفكرون والدعاة - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="text-center text-md-start mb-4 mb-md-0">
                <h1 class="page-title mb-2">
                    <i class="fas fa-lightbulb me-2 text-primary"></i>
                    المفكرون والدعاة
                </h1>
                <p class="text-muted fs-6">اقرأ مقالات المفكرين والدعاة المعاصرين وآرائهم في القضايا المختلفة</p>
            </div>
        </div>
        <div class="col-md-4">
            @auth
                @if(in_array(auth()->user()->user_type, ['admin', 'scholar', 'thinker', 'data_entry']))
                    <div class="d-flex gap-2 justify-content-center justify-content-md-end">
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            إضافة مقال
                        </a>
                        <a href="{{ route('articles.my') }}" class="btn btn-outline-primary">
                            <i class="fas fa-newspaper me-2"></i>
                            مقالاتي
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                    <h3 class="mb-1">{{ $islamicThoughtCount }}</h3>
                    <p class="text-muted mb-0 small">الفكر الإسلامي</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-mosque fa-2x text-primary mb-2"></i>
                    <h3 class="mb-1">{{ $dawahCount }}</h3>
                    <p class="text-muted mb-0 small">الدعوة والإرشاد</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                    <h3 class="mb-1">{{ $educationCount }}</h3>
                    <p class="text-muted mb-0 small">التربية والتعليم</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                    <h3 class="mb-1">{{ $societyCount }}</h3>
                    <p class="text-muted mb-0 small">المجتمع والأسرة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Thinkers -->
    @if($featuredThinkers->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star me-2"></i>
                        مفكرون مميزون
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($featuredThinkers as $thinker)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card hover-lift h-100">
                                <div class="card-body text-center">
                                    @if($thinker->profile_image)
                                        <img src="{{ asset('storage/' . $thinker->profile_image) }}" 
                                             alt="{{ $thinker->name }}"
                                             class="rounded-circle mb-3"
                                             style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                             style="width: 100px; height: 100px; font-size: 2.5rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <h5 class="mb-2">{{ $thinker->name }}</h5>
                                    @if($thinker->bio)
                                        <p class="text-muted small mb-3">{{ Str::limit($thinker->bio, 100) }}</p>
                                    @endif
                                    <a href="{{ route('thinkers.show', $thinker->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض الملف الشخصي
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Latest Articles -->
    @if($articles->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-newspaper me-2"></i>
                        أحدث المقالات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($articles as $article)
                        <div class="col-md-6 mb-4">
                            <div class="card hover-lift h-100">
                                @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $article->title }}"
                                     style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge badge-primary">{{ $article->category ?? 'عام' }}</span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $article->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <h5 class="card-title mb-2">{{ $article->title }}</h5>
                                    @if($article->excerpt)
                                        <p class="text-muted small mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            @if($article->author)
                                                <i class="fas fa-user me-1"></i>
                                                {{ $article->author->name }}
                                            @endif
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-eye me-1"></i>
                                            {{ $article->views_count ?? 0 }}
                                            <i class="fas fa-comment me-1 ms-2"></i>
                                            {{ $article->comments_count ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                        <i class="fas fa-book-open me-1"></i>
                                        قراءة المقال
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Articles Message -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted mb-2">لا توجد مقالات حالياً</h4>
                    <p class="text-muted">سيتم إضافة المقالات قريباً إن شاء الله</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Categories Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>
                        التصنيفات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-book-open fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">الفكر الإسلامي</h6>
                                        <small class="text-muted">{{ $islamicThoughtCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-mosque fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">الدعوة والإرشاد</h6>
                                        <small class="text-muted">{{ $dawahCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">التربية والتعليم</h6>
                                        <small class="text-muted">{{ $educationCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">المجتمع والأسرة</h6>
                                        <small class="text-muted">{{ $societyCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-child fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">قضايا الشباب</h6>
                                        <small class="text-muted">{{ $youthCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <a href="#" class="text-decoration-none">
                                <div class="card hover-lift text-center">
                                    <div class="card-body">
                                        <i class="fas fa-globe fa-2x text-primary mb-2"></i>
                                        <h6 class="mb-1">القضايا المعاصرة</h6>
                                        <small class="text-muted">{{ $contemporaryCount }} مقال</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

