@php
use App\Models\Rating;
@endphp

@extends('layouts.app')

@section('title', $article->meta_title ?? $article->title . ' - تمسيك')
@section('meta_description', $article->meta_description ?? $article->excerpt)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <!-- Main Content -->
        <div class="col-lg-10 col-xl-9">
            <!-- Article Header -->
            <article class="article-content">
                <!-- Featured Image -->
                @if($article->featured_image)
                    <div class="article-featured-image mb-4">
                        <img src="{{ asset('storage/' . $article->featured_image) }}" 
                             alt="{{ $article->title }}" 
                             class="img-fluid rounded shadow">
                    </div>
                @endif

                <!-- Title -->
                <h1 class="article-title mb-3">{{ $article->title }}</h1>

                <!-- Meta Info -->
                <div class="article-meta d-flex flex-wrap align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        @if($article->author->avatar)
                            <img src="{{ asset('storage/' . $article->author->avatar) }}" 
                                 alt="{{ $article->author->name }}" 
                                 class="rounded-circle me-2" 
                                 width="40" 
                                 height="40">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <div>
                            <strong>{{ $article->author->name }}</strong>
                            <small class="text-muted d-block">{{ $article->author->user_type === 'scholar' ? 'عالم' : ($article->author->user_type === 'thinker' ? 'مفكر' : 'كاتب') }}</small>
                        </div>
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $article->published_at ? $article->published_at->format('Y/m/d') : $article->created_at->format('Y/m/d') }}
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $article->reading_time ?? 5 }} دقائق قراءة
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-eye me-1"></i>
                        {{ $article->views_count ?? 0 }} مشاهدة
                    </div>
                </div>

                <!-- Excerpt -->
                @if($article->excerpt)
                    <div class="article-excerpt alert alert-light border-start border-primary border-4 mb-4">
                        <p class="mb-0 fw-bold">{{ $article->excerpt }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div class="article-body mb-5">
                    {!! $article->content !!}
                </div>

                <!-- Tags -->
                @if($article->tags)
                    @php
                        // فك تشفير JSON إذا كان string
                        if (is_string($article->tags)) {
                            $tagsArray = json_decode($article->tags, true);
                            // إذا فشل JSON decode، جرب explode
                            if (!is_array($tagsArray)) {
                                $tagsArray = explode(',', $article->tags);
                            }
                        } else {
                            $tagsArray = $article->tags;
                        }
                        $tagsArray = array_filter(array_map('trim', $tagsArray));
                    @endphp

                    @if(count($tagsArray) > 0)
                        <div class="article-tags mb-4">
                            <h6 class="mb-3">
                                <i class="fas fa-tags me-2"></i>
                                الكلمات المفتاحية:
                            </h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tagsArray as $tag)
                                    <span class="badge bg-success text-white" style="font-size: 0.9rem; padding: 8px 15px;">
                                        <i class="fas fa-tag me-1"></i>
                                        {!! $tag !!}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Social Share -->
                <div class="article-share card bg-light mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="fas fa-share-alt me-2"></i>
                            شارك المقال:
                        </h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->id)) }}" 
                               target="_blank" 
                               class="btn btn-primary btn-sm">
                                <i class="fab fa-facebook me-1"></i>
                                فيسبوك
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('articles.show', $article->id)) }}&text={{ urlencode($article->title) }}" 
                               target="_blank" 
                               class="btn btn-info btn-sm text-white">
                                <i class="fab fa-twitter me-1"></i>
                                تويتر
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($article->title . ' - ' . route('articles.show', $article->id)) }}" 
                               target="_blank" 
                               class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp me-1"></i>
                                واتساب
                            </a>
                            <a href="https://t.me/share/url?url={{ urlencode(route('articles.show', $article->id)) }}&text={{ urlencode($article->title) }}" 
                               target="_blank" 
                               class="btn btn-primary btn-sm">
                                <i class="fab fa-telegram me-1"></i>
                                تيليجرام
                            </a>
                            <button class="btn btn-secondary btn-sm" onclick="copyLink()">
                                <i class="fas fa-link me-1"></i>
                                نسخ الرابط
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Back Button & Like Button -->
                <div class="article-actions mb-4">
                    <div class="row g-3">
                        <!-- Back Button & Like Button -->
                        <div class="col-md-8">
                            <div class="d-flex gap-2 flex-wrap">
                                <!-- Back Button -->
                                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    رجوع
                                </button>

                                <!-- Like Button -->
                                @auth
                                    <button class="btn {{ $article->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }} btn-lg"
                                            id="likeBtn"
                                            onclick="toggleLike()">
                                        <i class="{{ $article->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart me-2" id="likeIcon"></i>
                                        <span id="likeCount">{{ $article->likes_count ?? 0 }}</span>
                                        إعجاب
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg">
                                        <i class="far fa-heart me-2"></i>
                                        <span>{{ $article->likes_count ?? 0 }}</span>
                                        إعجاب
                                    </a>
                                @endauth

                                <!-- Favorite Button -->
                                @auth
                                    @php
                                        $isFavorited = auth()->user()->favorites()
                                            ->where('favoritable_type', \App\Models\Article::class)
                                            ->where('favoritable_id', $article->id)
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
                        </div>

                        <!-- Rating Display -->
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded text-center h-100">
                                @php
                                    $avgRating = $article->getAverageRating();
                                    $ratingsCount = $article->getRatingsCount();
                                @endphp
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= round($avgRating) ? '#ffc107' : '#dee2e6' }}; font-size: 1.2rem;"></i>
                                    @endfor
                                </div>
                                <div class="mb-2">
                                    <strong id="avgRating" style="font-size: 1.2rem;">{{ number_format($avgRating, 1) }}</strong>
                                    <small class="text-muted">(<span id="ratingsCount">{{ $ratingsCount }}</span> تقييم)</small>
                                </div>
                                @auth
                                    <button onclick="openRatingModal()" class="btn btn-success btn-sm">
                                        <i class="fas fa-star me-1"></i>
                                        قيّم المقال
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-star me-1"></i>
                                        قيّم المقال
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="article-comments mt-5">
                    <h4 class="mb-4">
                        <i class="fas fa-comments me-2"></i>
                        التعليقات ({{ $article->comments->count() }})
                    </h4>

                    <!-- Add Comment Form -->
                    @auth
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('articles.comments.store', $article->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">أضف تعليقك:</label>
                                        <textarea class="form-control" 
                                                  id="comment" 
                                                  name="content" 
                                                  rows="3" 
                                                  placeholder="شارك برأيك..." 
                                                  required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        إرسال التعليق
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            يجب <a href="{{ route('login') }}">تسجيل الدخول</a> لإضافة تعليق
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="comments-list">
                        @forelse($article->comments as $comment)
                            <div class="comment-item card mb-3">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0">
                                            @if($comment->user->avatar)
                                                <img src="{{ asset('storage/' . $comment->user->avatar) }}" 
                                                     alt="{{ $comment->user->name }}" 
                                                     class="rounded-circle" 
                                                     width="40" 
                                                     height="40">
                                            @else
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-comments fa-3x mb-3"></i>
                                <p>لا توجد تعليقات بعد. كن أول من يعلق!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">عن الكاتب</h5>
                    @if($article->author->avatar)
                        <img src="{{ asset('storage/' . $article->author->avatar) }}" 
                             alt="{{ $article->author->name }}" 
                             class="rounded-circle mb-3" 
                             width="100" 
                             height="100">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                    @endif
                    <h6>{{ $article->author->name }}</h6>
                    @if($article->author->bio)
                        <p class="text-muted small">{{ Str::limit($article->author->bio, 100) }}</p>
                    @endif
                    <a href="{{ route('thinkers.show', $article->author->id) }}" class="btn btn-sm btn-outline-primary">
                        عرض الملف الشخصي
                    </a>
                </div>
            </div>

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-newspaper me-2"></i>
                            مقالات ذات صلة
                        </h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach($relatedArticles as $related)
                            <a href="{{ route('articles.show', $related->id) }}" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ Str::limit($related->title, 50) }}</h6>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $related->author->name }}
                                </small>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.article-title {
    font-size: 2.5rem;
    font-weight: bold;
    color: #2c5f2d;
    line-height: 1.3;
}

.article-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.article-body p {
    margin-bottom: 1.5rem;
}

.article-body h2, .article-body h3 {
    color: #2c5f2d;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1.5rem 0;
}

.comment-item {
    transition: all 0.3s ease;
}

.comment-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>

@push('scripts')
<script>
    function toggleLike() {
        const likeBtn = document.getElementById('likeBtn');
        const likeIcon = document.getElementById('likeIcon');
        const likeCount = document.getElementById('likeCount');

        // إرسال طلب AJAX
        fetch('{{ route("like.toggle", ["type" => "articles", "id" => $article->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // تحديث الواجهة
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
            alert('حدث خطأ أثناء الإعجاب. حاول مرة أخرى.');
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
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                favoritable_type: {!! json_encode(\App\Models\Article::class) !!},
                favoritable_id: {{ $article->id }}
            })
        })
        .then(response => {
            console.log('📡 Response status:', response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return response.text().then(text => {
                console.log('📄 Raw response:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('❌ JSON parse error:', e);
                    console.error('❌ Response text:', text);
                    throw new Error('Invalid JSON response');
                }
            });
        })
        .then(data => {
            console.log('✅ Response data:', data);
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء الحفظ. حاول مرة أخرى.');
        });
    }

    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            alert('تم نسخ الرابط بنجاح!');
        });
    }

    // Rating functionality
    let selectedRating = 0;

    function openRatingModal() {
        document.getElementById('ratingModal').style.display = 'flex';
    }

    function closeRatingModal() {
        document.getElementById('ratingModal').style.display = 'none';
        selectedRating = 0;
        document.querySelectorAll('.rating-star').forEach(star => {
            star.style.color = '#dee2e6';
        });
        document.getElementById('review').value = '';
    }

    function selectRating(rating) {
        selectedRating = rating;
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#dee2e6';
            }
        });
    }

    function submitRating() {
        if (selectedRating === 0) {
            alert('الرجاء اختيار تقييم');
            return;
        }

        const review = document.getElementById('review').value;

        fetch('{{ route("rating.store", ["type" => "articles", "id" => $article->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                rating: selectedRating,
                review: review
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                document.getElementById('avgRating').textContent = data.average_rating;
                document.getElementById('ratingsCount').textContent = data.ratings_count;
                closeRatingModal();
            } else {
                alert(data.message || 'حدث خطأ أثناء إرسال التقييم');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال التقييم');
        });
    }
</script>
@endpush

<!-- Rating Modal -->
@auth
<div id="ratingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px; text-align: center;">قيّم المقال</h3>
        <div style="text-align: center; margin-bottom: 20px;">
            @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star rating-star" onclick="selectRating({{ $i }})" style="font-size: 2rem; color: #dee2e6; cursor: pointer; margin: 0 5px;"></i>
            @endfor
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 10px; font-weight: bold;">تعليقك (اختياري):</label>
            <textarea id="review" rows="3" maxlength="1000" class="form-control" style="font-family: 'Amiri', serif;"></textarea>
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="closeRatingModal()" class="btn btn-secondary">إلغاء</button>
            <button onclick="submitRating()" class="btn btn-success">إرسال التقييم</button>
        </div>
    </div>
</div>
@endauth

@endsection

