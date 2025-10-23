@extends('layouts.app')

@section('title', 'الخطب - تمسيك')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/unified-theme.css') }}">
<style>
    .sermons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .sermon-card {
        background: white;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: all var(--transition-normal);
        border: 1px solid var(--border-light);
    }

    .sermon-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .sermon-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--text-light);
        padding: 20px;
        position: relative;
    }

    .sermon-category {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 10px;
    }

    .sermon-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 10px;
        line-height: 1.4;
        color: var(--text-light);
    }

    .sermon-title a {
        color: var(--text-light);
        text-decoration: none;
    }

    .sermon-title a:hover {
        text-decoration: underline;
    }

    .sermon-author {
        font-size: 0.9rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sermon-body {
        padding: 20px;
    }

    .sermon-excerpt {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .sermon-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--border-light);
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .sermon-stats {
        display: flex;
        gap: 15px;
    }

    .sermon-date {
        color: var(--text-muted);
    }

    .sermon-actions {
        margin-top: 15px;
    }

    .btn-view-sermon {
        display: block;
        width: 100%;
        padding: 12px 20px;
        background: linear-gradient(135deg, #1d8a4e, #0f7346);
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-view-sermon:hover {
        background: linear-gradient(135deg, #0f7346, #1d8a4e);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(29, 138, 78, 0.3);
        color: white;
    }

    .btn-view-sermon i {
        margin-left: 8px;
    }

    .search-section {
        background: var(--bg-white);
        padding: 25px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
        margin-bottom: 30px;
    }

    .search-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
    }

    .category-filter {
        min-width: 200px;
    }

    .no-sermons {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-muted);
    }

    .no-sermons i {
        font-size: 4rem;
        color: var(--border-color);
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .sermons-grid {
            grid-template-columns: 1fr;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input, .category-filter {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-2">
                <i class="fas fa-microphone me-2 text-primary"></i>
                الخطب الإسلامية
            </h1>
            <p class="section-subtitle">مجموعة متنوعة من الخطب الإسلامية من علماء اليمن الأجلاء</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
                @if(in_array(auth()->user()->user_type, ['admin', 'preacher', 'scholar', 'data_entry']))
                    <a href="{{ route('sermons.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        إضافة خطبة جديدة
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="search-section">
        <form method="GET" action="{{ route('sermons.index') }}" class="search-form">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="ابحث في عنوان أو محتوى الخطبة..."
                   class="form-control search-input">

            <select name="category" class="form-control form-select category-filter">
                <option value="all">جميع التصنيفات</option>
                @foreach($categories as $key => $name)
                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" class="form-control form-select category-filter">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>الأكثر مشاهدة</option>
                <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>الأكثر تحميلاً</option>
            </select>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>
                بحث
            </button>
        </form>
    </div>

    <!-- Results Info -->
    @if(request()->hasAny(['search', 'category', 'sort']))
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            تم العثور على {{ $sermons->total() }} خطبة
            @if(request('search'))
                للبحث: "<strong>{{ request('search') }}</strong>"
            @endif
            @if(request('category') && request('category') != 'all')
                في تصنيف: "<strong>{{ $categories[request('category')] ?? request('category') }}</strong>"
            @endif
        </div>
    @endif

    <!-- Sermons Grid -->
    @if($sermons->count() > 0)
        <div class="sermons-grid">
            @foreach($sermons as $sermon)
                <div class="sermon-card">
                    <div class="sermon-header">
                        <div class="sermon-category">
                            {{ $categories[$sermon->category] ?? $sermon->category }}
                        </div>
                        <h3 class="sermon-title">
                            <a href="{{ route('sermons.show', $sermon->id) }}">
                                {{ $sermon->title }}
                            </a>
                        </h3>
                        <div class="sermon-author">
                            <i class="fas fa-user"></i>
                            {{ $sermon->author->name }}
                            @if($sermon->scholar && $sermon->scholar->id != $sermon->author->id)
                                <span class="mx-1">|</span>
                                <i class="fas fa-user-graduate"></i>
                                {{ $sermon->scholar->name }}
                            @endif
                        </div>
                    </div>

                    <div class="sermon-body">
                        <p class="sermon-excerpt">
                            {{ Str::limit($sermon->introduction ?: strip_tags($sermon->content), 150) }}
                        </p>

                        <div class="sermon-meta">
                            <div class="sermon-stats">
                                <span>
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($sermon->views_count) }}
                                </span>
                                <span>
                                    <i class="fas fa-download"></i>
                                    {{ number_format($sermon->downloads_count) }}
                                </span>
                                @if($sermon->audio_file)
                                    <span>
                                        <i class="fas fa-volume-up"></i>
                                        صوتي
                                    </span>
                                @endif
                            </div>
                            <div class="sermon-date">
                                <i class="fas fa-calendar"></i>
                                {{ $sermon->published_at->format('Y/m/d') }}
                            </div>
                        </div>

                        <!-- زر عرض الخطبة -->
                        <div class="sermon-actions mt-3">
                            <a href="{{ route('sermons.show', $sermon->id) }}" class="btn-view-sermon">
                                <i class="fas fa-book-open me-2"></i>
                                عرض الخطبة
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-sermons">
            <i class="fas fa-search"></i>
            <h3>لم يتم العثور على خطب</h3>
            <p>
                @if(request()->hasAny(['search', 'category']))
                    جرب تغيير معايير البحث أو التصفية
                @else
                    لم يتم إضافة أي خطب بعد
                @endif
            </p>
            @if(!request()->hasAny(['search', 'category']))
                @auth
                    @if(in_array(auth()->user()->user_type, ['admin', 'preacher', 'scholar', 'data_entry']))
                        <a href="{{ route('sermons.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>
                            إضافة أول خطبة
                        </a>
                    @endif
                @endauth
            @else
                <a href="{{ route('sermons.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-list me-2"></i>
                    عرض جميع الخطب
                </a>
            @endif
        </div>
    @endif

    <!-- Pagination -->
    @if($sermons->hasPages())
        <div class="text-center mt-5">
            {{ $sermons->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
