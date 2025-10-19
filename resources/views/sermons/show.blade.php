@extends('layouts.app')

@section('title', $sermon->title . ' - تمسيك')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('sermons.index') }}">الخطب</a></li>
            <li class="breadcrumb-item active">{{ $sermon->title }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="card shadow-md">
                <!-- Header Image -->
                @if($sermon->image)
                    <img src="{{ asset('storage/sermons/' . $sermon->image) }}"
                         class="card-img-top rounded-top" alt="{{ $sermon->title }}"
                         style="height: 300px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <!-- Title and Meta -->
                    <header class="mb-4 pb-3 border-bottom">
                        <h1 class="page-title mb-3">{{ $sermon->title }}</h1>

                        <!-- Category and Featured Badge -->
                        <div class="mb-3">
                            <span class="badge badge-primary badge-lg">
                                <i class="fas fa-tag me-1"></i>
                                {{ $categories[$sermon->category] ?? $sermon->category }}
                            </span>
                            @if($sermon->is_featured)
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-star me-1"></i>
                                    مميزة
                                </span>
                            @endif
                        </div>

                        <!-- Author and Scholar Info -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-muted me-2"></i>
                                    <div>
                                        <strong>الكاتب:</strong> {{ $sermon->author->name }}
                                        @if($sermon->author->specialization)
                                            <br><small class="text-muted">{{ $sermon->author->specialization }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($sermon->scholar && $sermon->scholar->id != $sermon->author->id)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-user-graduate text-muted me-2"></i>
                                        <div>
                                            <strong>العالم:</strong> {{ $sermon->scholar->name }}
                                            @if($sermon->scholar->specialization)
                                                <br><small class="text-muted">{{ $sermon->scholar->specialization }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Date and Stats -->
                        <div class="row text-muted small">
                            <div class="col-md-6">
                                <i class="fas fa-calendar me-1"></i>
                                تاريخ النشر: {{ $sermon->published_at->format('Y/m/d') }}
                            </div>
                            <div class="col-md-6">
                                <i class="fas fa-eye me-1"></i>
                                {{ number_format($sermon->views_count) }} مشاهدة
                                <span class="ms-3">
                                    <i class="fas fa-download me-1"></i>
                                    {{ number_format($sermon->downloads_count) }} تحميل
                                </span>
                            </div>
                        </div>
                    </header>

                    <!-- Audio Player -->
                    @if($sermon->audio_file)
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-volume-up me-2"></i>
                                        الاستماع للخطبة
                                    </h6>
                                    <audio controls class="w-100">
                                        <source src="{{ asset('storage/sermons/audio/' . $sermon->audio_file) }}" type="audio/mpeg">
                                        متصفحك لا يدعم تشغيل الملفات الصوتية.
                                    </audio>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Introduction -->
                    @if($sermon->introduction)
                        <div class="mb-4">
                            <h3 class="h5 text-primary">مقدمة</h3>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($sermon->introduction)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="sermon-content mb-4">
                        <h3 class="h5 text-primary">محتوى الخطبة</h3>
                        <div class="content-text">
                            {!! nl2br(e($sermon->content)) !!}
                        </div>
                    </div>

                    <!-- Conclusion -->
                    @if($sermon->conclusion)
                        <div class="mb-4">
                            <h3 class="h5 text-primary">خاتمة</h3>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br(e($sermon->conclusion)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($sermon->tags && count($sermon->tags) > 0)
                        <div class="mb-4">
                            <h6 class="text-muted">الكلمات المفتاحية:</h6>
                            @foreach($sermon->tags as $tag)
                                <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- References -->
                    @if($sermon->references && count($sermon->references) > 0)
                        <div class="mb-4">
                            <h6 class="text-primary">المراجع:</h6>
                            <ul class="list-unstyled">
                                @foreach($sermon->references as $reference)
                                    <li class="mb-1">
                                        <i class="fas fa-book text-muted me-2"></i>
                                        {{ $reference }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <a href="{{ route('sermons.download', $sermon->id) }}" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i>
                            تحميل الخطبة
                        </a>
                        
                        @auth
                            <button type="button" class="btn btn-outline-danger favorite-btn" 
                                    data-sermon-id="{{ $sermon->id }}">
                                <i class="fas fa-heart me-2"></i>
                                {{ $isFavorited ? 'إزالة من المفضلات' : 'إضافة للمفضلات' }}
                            </button>
                        @endauth

                        <!-- Share Buttons -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-share me-2"></i>
                                مشاركة
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" onclick="shareOnFacebook()">
                                        <i class="fab fa-facebook me-2"></i>
                                        فيسبوك
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="shareOnTwitter()">
                                        <i class="fab fa-twitter me-2"></i>
                                        تويتر
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="shareOnWhatsApp()">
                                        <i class="fab fa-whatsapp me-2"></i>
                                        واتساب
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="copyLink()">
                                        <i class="fas fa-link me-2"></i>
                                        نسخ الرابط
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Sermons -->
            @if($relatedSermons && $relatedSermons->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-microphone me-2"></i>
                            خطب ذات صلة
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach($relatedSermons as $related)
                            <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                @if($related->image)
                                    <img src="{{ asset('storage/sermons/' . $related->image) }}" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" 
                                         alt="{{ $related->title }}">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-microphone text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('sermons.show', $related->id) }}" 
                                           class="text-decoration-none">
                                            {{ Str::limit($related->title, 50) }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        {{ $related->author->name }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Sermon Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الخطبة
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <strong>التصنيف:</strong>
                            <span class="badge bg-primary">
                                {{ $categories[$sermon->category] ?? $sermon->category }}
                            </span>
                        </li>
                        @if($sermon->difficulty_level)
                            <li class="mb-2">
                                <strong>مستوى الصعوبة:</strong>
                                @switch($sermon->difficulty_level)
                                    @case('beginner')
                                        <span class="badge bg-success">مبتدئ</span>
                                        @break
                                    @case('intermediate')
                                        <span class="badge bg-warning">متوسط</span>
                                        @break
                                    @case('advanced')
                                        <span class="badge bg-danger">متقدم</span>
                                        @break
                                @endswitch
                            </li>
                        @endif
                        @if($sermon->target_audience)
                            <li class="mb-2">
                                <strong>الجمهور المستهدف:</strong>
                                {{ $sermon->target_audience }}
                            </li>
                        @endif
                        @if($sermon->duration)
                            <li class="mb-2">
                                <strong>المدة:</strong>
                                {{ $sermon->duration }} دقيقة
                            </li>
                        @endif
                        <li class="mb-2">
                            <strong>عدد المشاهدات:</strong>
                            {{ number_format($sermon->views_count) }}
                        </li>
                        <li>
                            <strong>عدد التحميلات:</strong>
                            {{ number_format($sermon->downloads_count) }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.sermon-content .content-text {
    line-height: 1.8;
    font-size: 1.1rem;
}

.sermon-content h2, .sermon-content h3 {
    color: #2c5530;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.sermon-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.sermon-content ul, .sermon-content ol {
    margin-bottom: 1.5rem;
    padding-right: 2rem;
}

.sermon-content blockquote {
    border-right: 4px solid #2c5530;
    padding-right: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
}
</style>
@endpush

@push('scripts')
<script>
// Favorite functionality
document.addEventListener('DOMContentLoaded', function() {
    const favoriteBtn = document.querySelector('.favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const sermonId = this.dataset.sermonId;
            
            fetch(`/sermons/${sermonId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                const icon = this.querySelector('i');
                const text = this.querySelector('span') || this.childNodes[2];
                
                if (data.status === 'added') {
                    this.innerHTML = '<i class="fas fa-heart me-2"></i>إزالة من المفضلات';
                    this.classList.remove('btn-outline-danger');
                    this.classList.add('btn-danger');
                } else {
                    this.innerHTML = '<i class="fas fa-heart me-2"></i>إضافة للمفضلات';
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline-danger');
                }
                
                showToast(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('حدث خطأ أثناء إضافة/إزالة المفضلة', 'error');
            });
        });
    }
});

// Share functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $sermon->title }}');
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $sermon->title }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${title}`, '_blank');
}

function shareOnWhatsApp() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('{{ $sermon->title }}');
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
