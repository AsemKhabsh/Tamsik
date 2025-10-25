@extends('layouts.app')

@section('title', $fatwa->title)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('fatwas.index') }}">الفتاوى</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($fatwa->title, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="fatwa-detail-card">
                <!-- Header -->
                <div class="fatwa-detail-header">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        @php
                            $categories = [
                                'worship' => ['name' => 'العبادات', 'icon' => 'fa-pray', 'color' => '#1d8a4e'],
                                'transactions' => ['name' => 'المعاملات', 'icon' => 'fa-handshake', 'color' => '#3498db'],
                                'family' => ['name' => 'الأسرة', 'icon' => 'fa-home', 'color' => '#e74c3c'],
                                'contemporary' => ['name' => 'القضايا المعاصرة', 'icon' => 'fa-globe', 'color' => '#9b59b6'],
                                'ethics' => ['name' => 'الأخلاق والآداب', 'icon' => 'fa-heart', 'color' => '#e67e22'],
                                'beliefs' => ['name' => 'العقيدة', 'icon' => 'fa-star-and-crescent', 'color' => '#16a085'],
                                'jurisprudence' => ['name' => 'الفقه', 'icon' => 'fa-balance-scale', 'color' => '#2c3e50'],
                                'quran' => ['name' => 'القرآن الكريم', 'icon' => 'fa-book-quran', 'color' => '#27ae60'],
                                'hadith' => ['name' => 'الحديث الشريف', 'icon' => 'fa-scroll', 'color' => '#d35400'],
                            ];
                            $categoryInfo = $categories[$fatwa->category] ?? ['name' => $fatwa->category, 'icon' => 'fa-tag', 'color' => '#1d8a4e'];
                        @endphp
                        <span class="fatwa-category-badge" style="background-color: {{ $categoryInfo['color'] }};">
                            <i class="fas {{ $categoryInfo['icon'] }} me-1"></i>
                            {{ $categoryInfo['name'] }}
                        </span>
                        @if($fatwa->is_featured)
                            <span class="featured-badge">
                                <i class="fas fa-star me-1"></i>
                                مميزة
                            </span>
                        @endif
                    </div>

                    <h1 class="fatwa-detail-title">{{ $fatwa->title }}</h1>

                    <div class="fatwa-meta-bar">
                        @if($fatwa->scholar)
                        <div class="meta-item">
                            <i class="fas fa-user-graduate"></i>
                            <a href="{{ route('fatwas.scholar', $fatwa->scholar_id) }}">{{ $fatwa->scholar->name }}</a>
                        </div>
                        @endif
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $fatwa->published_at ? $fatwa->published_at->format('d/m/Y') : $fatwa->created_at->format('d/m/Y') }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-eye"></i>
                            {{ number_format($fatwa->views_count ?? 0) }}
                        </div>
                        @if($fatwa->likes_count > 0)
                        <div class="meta-item">
                            <i class="fas fa-heart text-danger"></i>
                            {{ number_format($fatwa->likes_count) }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Question -->
                <div class="fatwa-section question-section">
                    <div class="section-header">
                        <div class="section-icon question-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h3 class="section-title">السؤال</h3>
                    </div>
                    <div class="section-content question-content">
                        {!! nl2br(e($fatwa->question)) !!}
                    </div>
                </div>

                <!-- Answer -->
                <div class="fatwa-section answer-section">
                    <div class="section-header">
                        <div class="section-icon answer-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="section-title">الجواب</h3>
                    </div>
                    <div class="section-content answer-content">
                        {!! nl2br(e($fatwa->answer)) !!}
                    </div>
                </div>

                <!-- Tags (if available) -->
                @if($fatwa->tags && count($fatwa->tags) > 0)
                    <div class="fatwa-tags mb-4">
                        <i class="fas fa-tags me-2"></i>
                        @foreach($fatwa->tags as $tag)
                            <span class="tag-badge">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- References (if available) -->
                @if($fatwa->references && count($fatwa->references) > 0)
                    <div class="fatwa-section references-section">
                        <div class="section-header">
                            <div class="section-icon references-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h3 class="section-title">المراجع والمصادر</h3>
                        </div>
                        <ul class="references-list">
                            @foreach($fatwa->references as $index => $reference)
                                <li>
                                    <span class="reference-number">{{ $index + 1 }}</span>
                                    {{ $reference }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="fatwa-actions-bar">
                    <div class="actions-group">
                        @auth
                            <button class="action-btn like-btn {{ $fatwa->isLikedBy(auth()->user()) ? 'active' : '' }}"
                                    id="likeBtn"
                                    onclick="toggleLike()">
                                <i class="{{ $fatwa->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart" id="likeIcon"></i>
                                <span class="action-text">إعجاب</span>
                                <span class="action-count" id="likeCount">{{ $fatwa->likes_count ?? 0 }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="action-btn like-btn">
                                <i class="far fa-heart"></i>
                                <span class="action-text">إعجاب</span>
                                <span class="action-count">{{ $fatwa->likes_count ?? 0 }}</span>
                            </a>
                        @endauth

                        @auth
                            @php
                                $isFavorited = auth()->user()->favorites()
                                    ->where('favoritable_type', \App\Models\Fatwa::class)
                                    ->where('favoritable_id', $fatwa->id)
                                    ->exists();
                            @endphp
                            <button class="action-btn favorite-btn {{ $isFavorited ? 'active' : '' }}"
                                    id="favoriteBtn"
                                    onclick="toggleFavorite()">
                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-bookmark" id="favoriteIcon"></i>
                                <span class="action-text">{{ $isFavorited ? 'محفوظة' : 'حفظ' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="action-btn favorite-btn">
                                <i class="far fa-bookmark"></i>
                                <span class="action-text">حفظ</span>
                            </a>
                        @endauth

                        <button class="action-btn share-btn" onclick="shareFatwa()">
                            <i class="fas fa-share-alt"></i>
                            <span class="action-text">مشاركة</span>
                        </button>

                        <button class="action-btn print-btn" onclick="window.print()">
                            <i class="fas fa-print"></i>
                            <span class="action-text">طباعة</span>
                        </button>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="share-section mt-4">
                    <h5 class="mb-3">شارك هذه الفتوى:</h5>
                    <div class="share-buttons">
                        <a href="#" class="btn btn-outline-primary btn-sm" onclick="shareOnFacebook(); return false;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm" onclick="shareOnTwitter(); return false;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm" onclick="shareOnWhatsApp(); return false;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-sm" onclick="copyLink(); return false;">
                            <i class="fas fa-link"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Scholar Info -->
            @if($fatwa->scholar)
            <div class="sidebar-card scholar-card mb-4">
                <div class="scholar-card-header">
                    <div class="scholar-avatar-large">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="scholar-card-body text-center">
                    <h4 class="scholar-name mb-2">{{ $fatwa->scholar->name }}</h4>
                    <p class="scholar-title text-muted mb-3">
                        <i class="fas fa-graduation-cap me-1"></i>
                        عالم وداعية إسلامي
                    </p>

                    @if($fatwa->scholar->bio)
                        <p class="scholar-bio text-muted small mb-3">
                            {{ Str::limit($fatwa->scholar->bio, 100) }}
                        </p>
                    @endif

                    <div class="scholar-stats mb-3">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-item">
                                    <i class="fas fa-book-open text-success"></i>
                                    <div class="stat-number">{{ $fatwa->scholar->fatwas()->published()->count() }}</div>
                                    <div class="stat-label">فتوى</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-item">
                                    <i class="fas fa-eye text-info"></i>
                                    <div class="stat-number">{{ number_format($fatwa->scholar->fatwas()->sum('views_count') ?? 0) }}</div>
                                    <div class="stat-label">مشاهدة</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="scholar-actions">
                        <a href="{{ route('scholars.show', $fatwa->scholar_id) }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-user me-2"></i>
                            صفحة العالم
                        </a>
                        <a href="{{ route('fatwas.scholar', $fatwa->scholar_id) }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-balance-scale me-2"></i>
                            جميع فتاوى العالم
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Fatwas -->
            @if($relatedFatwas->count() > 0)
                <div class="sidebar-card">
                    <h4 class="sidebar-title">
                        <i class="fas fa-list me-2"></i>
                        فتاوى ذات صلة
                    </h4>
                    <div class="related-fatwas">
                        @foreach($relatedFatwas as $related)
                            <div class="related-fatwa-item">
                                <a href="{{ route('fatwas.show', $related->id) }}">
                                    {{ Str::limit($related->title, 60) }}
                                </a>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($related->views_count ?? 0) }}
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Ask Question CTA -->
            @auth
                @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
                    <div class="sidebar-card bg-success text-white">
                        <h4 class="sidebar-title text-white">
                            <i class="fas fa-question-circle me-2"></i>
                            هل لديك سؤال؟
                        </h4>
                        <p class="mb-3">اطرح سؤالك وسيجيب عليه أحد علمائنا</p>
                        <a href="{{ route('questions.ask') }}" class="btn btn-light w-100">
                            <i class="fas fa-pen me-2"></i>
                            اطرح سؤالك
                        </a>
                    </div>
                @endif
            @else
                <div class="sidebar-card bg-success text-white">
                    <h4 class="sidebar-title text-white">
                        <i class="fas fa-question-circle me-2"></i>
                        هل لديك سؤال؟
                    </h4>
                    <p class="mb-3">اطرح سؤالك وسيجيب عليه أحد علمائنا</p>
                    <a href="{{ route('login') }}" class="btn btn-light w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        سجل دخولك
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

<style>
.fatwa-detail-card {
    background: white;
    padding: 0;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    overflow: hidden;
}

.fatwa-detail-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 30px 40px;
    border-bottom: 3px solid #1d8a4e;
}

.fatwa-category-badge {
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.featured-badge {
    background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%);
    color: white;
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
}

.fatwa-detail-title {
    color: #2c3e50;
    font-size: 2.2rem;
    font-weight: bold;
    margin-bottom: 20px;
    line-height: 1.4;
}

.fatwa-meta-bar {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    padding: 15px 0;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 0.95rem;
    font-weight: 500;
}

.meta-item i {
    color: #1d8a4e;
    font-size: 1.1rem;
}

.meta-item a {
    color: #1d8a4e;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.meta-item a:hover {
    color: #26a65b;
    text-decoration: underline;
}

.fatwa-section {
    padding: 25px 40px;
    margin-bottom: 0;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px !important;
    margin-bottom: 15px;
}

.section-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    flex-shrink: 0;
    margin: 0 !important;
}

.question-icon {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
}

.answer-icon {
    background: linear-gradient(135deg, #1d8a4e 0%, #26a65b 100%);
}

.references-icon {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
}

.section-title {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0;
    line-height: 1.2;
}

.section-content {
    font-size: 1.15rem;
    line-height: 2;
    color: #34495e;
    text-align: justify;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-right: 4px solid #1d8a4e;
}

.question-section {
    background: #f0f8ff;
    border-right: 5px solid #3498db;
}

.question-content {
    background: white;
    border-right: 4px solid #3498db;
    font-weight: 500;
}

.answer-section {
    background: #f0fdf4;
    border-right: 5px solid #1d8a4e;
}

.answer-content {
    background: white;
    border-right: 4px solid #1d8a4e;
}

.references-section {
    background: #faf5ff;
    border-right: 5px solid #9b59b6;
}

.references-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.references-list li {
    padding: 15px 20px 15px 60px;
    background: white;
    margin-bottom: 12px;
    border-radius: 8px;
    border-right: 4px solid #9b59b6;
    position: relative;
    font-size: 1.05rem;
    line-height: 1.7;
    color: #34495e;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.reference-number {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.fatwa-tags {
    padding: 25px 40px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.tag-badge {
    display: inline-block;
    background: white;
    color: #1d8a4e;
    padding: 6px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    margin: 0 5px 5px 0;
    border: 2px solid #1d8a4e;
    font-weight: 500;
    transition: all 0.3s ease;
}

.tag-badge:hover {
    background: #1d8a4e;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(29, 138, 78, 0.3);
}

.fatwa-actions-bar {
    padding: 25px 40px;
    background: white;
    border-top: 2px solid #e9ecef;
}

.actions-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 8px !important;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 600;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.action-btn i {
    font-size: 1.1rem;
    margin: 0 !important;
}

.action-text {
    font-weight: 600;
    margin: 0 !important;
}

.action-count {
    background: #f8f9fa;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: bold;
    margin: 0 !important;
}

.like-btn {
    border-color: #e74c3c;
    color: #e74c3c;
}

.like-btn:hover {
    background: #e74c3c;
    color: white;
}

.like-btn.active {
    background: #e74c3c;
    color: white;
    border-color: #e74c3c;
}

.like-btn.active .action-count {
    background: rgba(255,255,255,0.3);
    color: white;
}

.favorite-btn {
    border-color: #f39c12;
    color: #f39c12;
}

.favorite-btn:hover {
    background: #f39c12;
    color: white;
}

.favorite-btn.active {
    background: #f39c12;
    color: white;
    border-color: #f39c12;
}

.share-btn {
    border-color: #3498db;
    color: #3498db;
}

.share-btn:hover {
    background: #3498db;
    color: white;
}

.print-btn {
    border-color: #95a5a6;
    color: #95a5a6;
}

.print-btn:hover {
    background: #95a5a6;
    color: white;
}

.share-section {
    text-align: center;
    padding: 20px 40px;
    background: white;
}

.share-section h5 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 15px;
}

.share-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: center;
}

.sidebar-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Scholar Card Styles */
.scholar-card {
    padding: 0;
    overflow: hidden;
    border: 2px solid #1d8a4e;
}

.scholar-card-header {
    background: linear-gradient(135deg, #1d8a4e 0%, #26a65b 100%);
    padding: 30px 20px 20px;
    text-align: center;
    position: relative;
}

.scholar-avatar-large {
    width: 100px;
    height: 100px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border: 4px solid #fff;
}

.scholar-avatar-large i {
    font-size: 3rem;
    color: #1d8a4e;
}

.scholar-card-body {
    padding: 25px;
}

.scholar-name {
    color: #1d8a4e;
    font-size: 1.4rem;
    font-weight: bold;
    margin-top: 10px;
}

.scholar-title {
    font-size: 0.95rem;
    color: #666;
}

.scholar-bio {
    font-size: 0.9rem;
    line-height: 1.6;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
    border-right: 3px solid #1d8a4e;
}

.scholar-stats {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.stat-item {
    padding: 10px;
}

.stat-item i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #1d8a4e;
    margin: 5px 0;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
}

.scholar-actions .btn {
    font-weight: 500;
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.scholar-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(29, 138, 78, 0.3);
}

.sidebar-title {
    color: #1d8a4e;
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1d8a4e;
}

.related-fatwa-item {
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.related-fatwa-item:last-child {
    border-bottom: none;
}

.related-fatwa-item a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
}

.related-fatwa-item a:hover {
    color: #1d8a4e;
}

/* Responsive Design */
@media (max-width: 768px) {
    .fatwa-detail-card {
        border-radius: 10px;
    }

    .fatwa-detail-header {
        padding: 20px;
    }

    .fatwa-detail-title {
        font-size: 1.6rem;
    }

    .fatwa-meta-bar {
        gap: 15px;
    }

    .meta-item {
        font-size: 0.85rem;
    }

    .fatwa-section {
        padding: 18px 20px;
    }

    .section-header {
        gap: 8px !important;
        margin-bottom: 12px;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        font-size: 1.1rem;
        margin: 0 !important;
    }

    .section-title {
        font-size: 1.25rem;
    }

    .section-content {
        font-size: 1.05rem;
        padding: 15px;
    }

    .fatwa-actions-bar {
        padding: 20px;
    }

    .actions-group {
        gap: 8px;
    }

    .action-btn {
        padding: 10px 16px;
        font-size: 0.9rem;
        gap: 6px !important;
    }

    .action-btn i {
        font-size: 1rem;
    }

    .action-text {
        display: none;
    }

    .action-btn i {
        margin: 0;
    }

    .fatwa-tags {
        padding: 18px 20px;
    }

    .share-section {
        padding: 18px 20px;
    }

    .share-section h5 {
        font-size: 1.1rem;
    }

    .share-buttons {
        gap: 8px;
    }

    .references-list li {
        padding: 12px 15px 12px 50px;
        font-size: 0.95rem;
    }

    .reference-number {
        width: 25px;
        height: 25px;
        font-size: 0.8rem;
    }
}

/* Print Styles */
@media print {
    .fatwa-actions-bar,
    .sidebar-card,
    .breadcrumb,
    nav,
    footer {
        display: none !important;
    }

    .fatwa-detail-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }

    .col-lg-8 {
        width: 100%;
        max-width: 100%;
    }
}
</style>
@endsection

@push('scripts')
<script>
// Like functionality
function toggleLike() {
    const likeBtn = document.getElementById('likeBtn');
    const likeIcon = document.getElementById('likeIcon');
    const likeCount = document.getElementById('likeCount');

    fetch('{{ route("like.toggle", ["type" => "fatwas", "id" => $fatwa->id]) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_liked) {
                likeIcon.classList.remove('far');
                likeIcon.classList.add('fas');
                likeBtn.classList.add('active');
            } else {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far');
                likeBtn.classList.remove('active');
            }
            likeCount.textContent = data.likes_count;
            showToast(data.is_liked ? 'تم الإعجاب بالفتوى' : 'تم إلغاء الإعجاب');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ أثناء الإعجاب. حاول مرة أخرى.', 'error');
    });
}

// Favorite functionality
function toggleFavorite() {
    const favoriteBtn = document.getElementById('favoriteBtn');
    const favoriteIcon = document.getElementById('favoriteIcon');

    fetch('{{ route("favorites.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            favoritable_type: '{{ \App\Models\Fatwa::class }}',
            favoritable_id: {{ $fatwa->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_favorited) {
                favoriteIcon.classList.remove('far');
                favoriteIcon.classList.add('fas');
                favoriteBtn.classList.add('active');
                favoriteBtn.querySelector('.action-text').textContent = 'محفوظة';
            } else {
                favoriteIcon.classList.remove('fas');
                favoriteIcon.classList.add('far');
                favoriteBtn.classList.remove('active');
                favoriteBtn.querySelector('.action-text').textContent = 'حفظ';
            }
            showToast(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ أثناء الحفظ. حاول مرة أخرى.', 'error');
    });
}

// Share functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $fatwa->question }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

// Share functionality
function shareFatwa() {
    const shareData = {
        title: '{{ $fatwa->title }}',
        text: '{{ Str::limit($fatwa->question, 100) }}',
        url: window.location.href
    };

    // Check if Web Share API is supported
    if (navigator.share) {
        navigator.share(shareData)
            .then(() => console.log('Shared successfully'))
            .catch((error) => console.log('Error sharing:', error));
    } else {
        // Fallback: Copy link to clipboard
        copyLink();
    }
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $fatwa->title }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $fatwa->title }}');
    window.open(`https://wa.me/?text=${title} ${url}`, '_blank');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        showToast('تم نسخ الرابط بنجاح');
    }, function() {
        showToast('فشل في نسخ الرابط', 'error');
    });
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} position-fixed`;
    toast.style.cssText = 'top: 20px; left: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    document.body.appendChild(toast);

    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}
</script>
@endpush
