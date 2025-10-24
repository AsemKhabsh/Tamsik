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
                    <span class="fatwa-category">{{ $fatwa->category }}</span>
                    <h1 class="fatwa-detail-title">{{ $fatwa->title }}</h1>
                    
                    <div class="fatwa-meta">
                        <span>
                            <i class="fas fa-user-graduate"></i>
                            <a href="{{ route('fatwas.scholar', $fatwa->scholar_id) }}">{{ $fatwa->scholar->name }}</a>
                        </span>
                        <span>
                            <i class="fas fa-calendar"></i>
                            {{ $fatwa->published_at ? $fatwa->published_at->format('d/m/Y') : $fatwa->created_at->format('d/m/Y') }}
                        </span>
                        <span>
                            <i class="fas fa-eye"></i>
                            {{ number_format($fatwa->views_count ?? 0) }} مشاهدة
                        </span>
                    </div>
                </div>

                <!-- Question -->
                <div class="fatwa-section">
                    <h3 class="section-title">
                        <i class="fas fa-question-circle me-2"></i>
                        السؤال
                    </h3>
                    <div class="section-content">
                        {!! nl2br(e($fatwa->question)) !!}
                    </div>
                </div>

                <!-- Answer -->
                <div class="fatwa-section">
                    <h3 class="section-title">
                        <i class="fas fa-check-circle me-2"></i>
                        الجواب
                    </h3>
                    <div class="section-content">
                        {!! nl2br(e($fatwa->answer)) !!}
                    </div>
                </div>

                <!-- References (if available) -->
                @if($fatwa->references && count($fatwa->references) > 0)
                    <div class="fatwa-section">
                        <h3 class="section-title">
                            <i class="fas fa-book me-2"></i>
                            المراجع
                        </h3>
                        <ul class="references-list">
                            @foreach($fatwa->references as $reference)
                                <li>{{ $reference }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Like Button -->
                <div class="fatwa-actions mb-4">
                    @auth
                        <button class="btn {{ $fatwa->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }} btn-lg"
                                id="likeBtn"
                                onclick="toggleLike()">
                            <i class="{{ $fatwa->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart me-2" id="likeIcon"></i>
                            <span id="likeCount">{{ $fatwa->likes_count ?? 0 }}</span>
                            إعجاب
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg">
                            <i class="far fa-heart me-2"></i>
                            <span>{{ $fatwa->likes_count ?? 0 }}</span>
                            إعجاب
                        </a>
                    @endauth
                </div>

                <!-- Favorite Button -->
                <div class="mt-3">
                    @auth
                        @php
                            $isFavorited = auth()->user()->favorites()
                                ->where('favoritable_type', \App\Models\Fatwa::class)
                                ->where('favoritable_id', $fatwa->id)
                                ->exists();
                        @endphp
                        <button class="btn {{ $isFavorited ? 'btn-warning' : 'btn-outline-warning' }} btn-lg"
                                id="favoriteBtn"
                                onclick="toggleFavorite()">
                            <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-bookmark me-2" id="favoriteIcon"></i>
                            {{ $isFavorited ? 'محفوظة' : 'حفظ' }}
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-warning btn-lg">
                            <i class="far fa-bookmark me-2"></i>
                            حفظ
                        </a>
                    @endauth
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
            <div class="sidebar-card mb-4">
                <h4 class="sidebar-title">
                    <i class="fas fa-user-graduate me-2"></i>
                    العالم المجيب
                </h4>
                <div class="scholar-info">
                    <div class="scholar-avatar-small mb-3">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h5>{{ $fatwa->scholar->name }}</h5>
                    <p class="text-muted">عالم وداعية إسلامي</p>
                    <a href="{{ route('scholars.show', $fatwa->scholar_id) }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-user me-2"></i>
                        صفحة العالم
                    </a>
                    <a href="{{ route('fatwas.scholar', $fatwa->scholar_id) }}" class="btn btn-outline-success btn-sm w-100 mt-2">
                        <i class="fas fa-balance-scale me-2"></i>
                        فتاوى العالم
                    </a>
                </div>
            </div>

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
            <div class="sidebar-card bg-success text-white">
                <h4 class="sidebar-title text-white">
                    <i class="fas fa-question-circle me-2"></i>
                    هل لديك سؤال؟
                </h4>
                <p class="mb-3">اطرح سؤالك وسيجيب عليه أحد علمائنا</p>
                @auth
                    <a href="{{ route('questions.ask') }}" class="btn btn-light w-100">
                        <i class="fas fa-pen me-2"></i>
                        اطرح سؤالك
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        سجل دخولك
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
.fatwa-detail-card {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.fatwa-detail-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #1d8a4e;
}

.fatwa-category {
    background: #1d8a4e;
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 0.85rem;
    display: inline-block;
    margin-bottom: 15px;
}

.fatwa-detail-title {
    color: #1d8a4e;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 15px;
}

.fatwa-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    color: #666;
    font-size: 0.95rem;
}

.fatwa-meta a {
    color: #1d8a4e;
    text-decoration: none;
}

.fatwa-meta a:hover {
    text-decoration: underline;
}

.fatwa-section {
    margin-bottom: 30px;
}

.section-title {
    color: #1d8a4e;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 15px;
}

.section-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
    text-align: justify;
}

.references-list {
    list-style: none;
    padding: 0;
}

.references-list li {
    padding: 10px;
    background: #f8f9fa;
    margin-bottom: 10px;
    border-radius: 5px;
    border-right: 3px solid #1d8a4e;
}

.share-buttons {
    display: flex;
    gap: 10px;
}

.sidebar-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.sidebar-title {
    color: #1d8a4e;
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 20px;
}

.scholar-avatar-small {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1d8a4e, #d4af37);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    margin: 0 auto;
}

.scholar-info {
    text-align: center;
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
                likeBtn.classList.remove('btn-outline-danger');
                likeBtn.classList.add('btn-danger');
            } else {
                likeIcon.classList.remove('fas');
                likeIcon.classList.add('far');
                likeBtn.classList.remove('btn-danger');
                likeBtn.classList.add('btn-outline-danger');
            }
            likeCount.textContent = data.likes_count;
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
                favoriteBtn.classList.remove('btn-outline-warning');
                favoriteBtn.classList.add('btn-warning');
                favoriteBtn.innerHTML = '<i class="fas fa-bookmark me-2"></i> محفوظة';
            } else {
                favoriteIcon.classList.remove('fas');
                favoriteIcon.classList.add('far');
                favoriteBtn.classList.remove('btn-warning');
                favoriteBtn.classList.add('btn-outline-warning');
                favoriteBtn.innerHTML = '<i class="far fa-bookmark me-2"></i> حفظ';
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

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $fatwa->question }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $fatwa->question }}');
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
