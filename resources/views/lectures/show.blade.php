@php
use App\Models\Rating;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lecture->title }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lectures.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Amiri', serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .lecture-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .lecture-title {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .lecture-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }
        
        .meta-item i {
            color: #1d8a4e;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .status-scheduled {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-completed {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .lecture-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .lecture-description {
            font-size: 1.2rem;
            line-height: 2;
            text-align: justify;
        }
        
        .back-link {
            color: #1d8a4e;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .speaker-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .speaker-name {
            color: #1d8a4e;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .location-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .countdown {
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .countdown-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .countdown-timer {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('lectures.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة المحاضرات
        </a>
        
        <div class="lecture-header">
            <h1 class="lecture-title">{{ $lecture->title }}</h1>
            
            <span class="status-badge status-{{ $lecture->status }}">
                @if($lecture->status == 'scheduled')
                    <i class="fas fa-clock"></i> مجدولة
                @elseif($lecture->status == 'completed')
                    <i class="fas fa-check-circle"></i> مكتملة
                @elseif($lecture->status == 'cancelled')
                    <i class="fas fa-times-circle"></i> ملغية
                @endif
            </span>
            
            <div class="lecture-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $lecture->scheduled_at->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $lecture->scheduled_at->format('h:i A') }}</span>
                </div>
                @if($lecture->location)
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $lecture->location }}</span>
                    </div>
                @endif
                @if($lecture->duration)
                    <div class="meta-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span>{{ $lecture->duration }} دقيقة</span>
                    </div>
                @endif
            </div>
            
            @if($lecture->status == 'scheduled' && $lecture->scheduled_at->isFuture())
                <div class="countdown">
                    <div class="countdown-title">الوقت المتبقي للمحاضرة</div>
                    <div class="countdown-timer" id="countdown">
                        <!-- سيتم تحديثه بـ JavaScript -->
                    </div>
                </div>
            @endif
        </div>
        
        <div class="lecture-content">
            <h3 style="color: #1d8a4e; margin-bottom: 20px;">وصف المحاضرة</h3>
            <div class="lecture-description">
                {!! nl2br(e($lecture->description)) !!}
            </div>
            
            @if($lecture->speaker)
                <div class="speaker-info">
                    <h4 style="color: #1d8a4e; margin-bottom: 10px;">المحاضر</h4>
                    <div class="speaker-name">{{ $lecture->speaker->name }}</div>
                    @if($lecture->speaker->bio)
                        <p style="margin-top: 10px; color: #666;">{{ Str::limit($lecture->speaker->bio, 200) }}</p>
                    @endif
                </div>
            @endif
            
            @if($lecture->location)
                <div class="location-info">
                    <h4 style="color: #856404; margin-bottom: 10px;">
                        <i class="fas fa-map-marker-alt"></i>
                        مكان المحاضرة
                    </h4>
                    <p style="margin: 0; color: #856404;">{{ $lecture->location }}</p>
                </div>
            @endif

            <!-- Like, Favorite and Rating Section -->
            <div class="lecture-actions" style="margin-top: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <!-- Like Button -->
                    <div>
                        @auth
                            <button class="btn {{ $lecture->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }} btn-lg w-100"
                                    id="likeBtn"
                                    onclick="toggleLike()"
                                    style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #dc3545; background: {{ $lecture->isLikedBy(auth()->user()) ? '#dc3545' : 'white' }}; color: {{ $lecture->isLikedBy(auth()->user()) ? 'white' : '#dc3545' }}; cursor: pointer; transition: all 0.3s;">
                                <i class="{{ $lecture->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart" id="likeIcon" style="margin-left: 8px;"></i>
                                <span id="likeCount">{{ $lecture->likes_count ?? 0 }}</span>
                                إعجاب
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                               style="display: block; width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #dc3545; background: white; color: #dc3545; text-align: center; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                                <i class="far fa-heart" style="margin-left: 8px;"></i>
                                <span>{{ $lecture->likes_count ?? 0 }}</span>
                                إعجاب
                            </a>
                        @endauth
                    </div>

                    <!-- Favorite Button -->
                    <div>
                        @auth
                            @php
                                $isFavorited = auth()->user()->favorites()
                                    ->where('favoritable_type', \App\Models\Lecture::class)
                                    ->where('favoritable_id', $lecture->id)
                                    ->exists();
                            @endphp
                            <button class="btn {{ $isFavorited ? 'btn-warning' : 'btn-outline-warning' }} btn-lg w-100"
                                    id="favoriteBtn"
                                    onclick="toggleFavorite()"
                                    style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #ffc107; background: {{ $isFavorited ? '#ffc107' : 'white' }}; color: {{ $isFavorited ? 'white' : '#ffc107' }}; cursor: pointer; transition: all 0.3s;">
                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-bookmark" id="favoriteIcon" style="margin-left: 8px;"></i>
                                {{ $isFavorited ? 'محفوظة' : 'حفظ' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                               style="display: block; width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #ffc107; background: white; color: #ffc107; text-align: center; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                                <i class="far fa-bookmark" style="margin-left: 8px;"></i>
                                حفظ
                            </a>
                        @endauth
                    </div>

                    <!-- Rating Display -->
                    <div>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; height: 100%;">
                            @php
                                $avgRating = $lecture->getAverageRating();
                                $ratingsCount = $lecture->getRatingsCount();
                            @endphp
                            <div style="margin-bottom: 10px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= round($avgRating) ? '#ffc107' : '#dee2e6' }}; font-size: 1.5rem;"></i>
                                @endfor
                            </div>
                            <div style="margin-bottom: 10px;">
                                <strong id="avgRating" style="font-size: 1.3rem;">{{ number_format($avgRating, 1) }}</strong>
                                <small style="color: #6c757d;">(<span id="ratingsCount">{{ $ratingsCount }}</span> تقييم)</small>
                            </div>
                            @auth
                                <button onclick="openRatingModal()"
                                        style="padding: 8px 20px; background: #1d8a4e; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 0.95rem;">
                                    <i class="fas fa-star" style="margin-left: 5px;"></i>
                                    قيّم المحاضرة
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                   style="display: inline-block; padding: 8px 20px; background: #1d8a4e; color: white; border-radius: 5px; text-decoration: none; font-size: 0.95rem;">
                                    <i class="fas fa-star" style="margin-left: 5px;"></i>
                                    قيّم المحاضرة
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    @auth
    <div id="ratingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #1d8a4e;">تقييم المحاضرة</h3>
                <button onclick="closeRatingModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #999;">&times;</button>
            </div>
            <div style="margin-bottom: 20px; text-align: center;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">اختر التقييم:</label>
                <div id="ratingStars" style="font-size: 2.5rem;">
                    <i class="far fa-star" data-rating="1" style="cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                    <i class="far fa-star" data-rating="2" style="cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                    <i class="far fa-star" data-rating="3" style="cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                    <i class="far fa-star" data-rating="4" style="cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                    <i class="far fa-star" data-rating="5" style="cursor: pointer; margin: 0 5px; transition: all 0.2s;"></i>
                </div>
                <input type="hidden" id="ratingValue">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">تعليقك (اختياري):</label>
                <textarea id="review" rows="3" maxlength="1000" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-family: 'Amiri', serif;"></textarea>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button onclick="closeRatingModal()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer;">إلغاء</button>
                <button onclick="submitRating()" style="padding: 10px 20px; background: #1d8a4e; color: white; border: none; border-radius: 5px; cursor: pointer;">إرسال التقييم</button>
            </div>
        </div>
    </div>
    @endauth
    
    <script>
        // Like functionality
        function toggleLike() {
            const likeBtn = document.getElementById('likeBtn');
            const likeIcon = document.getElementById('likeIcon');
            const likeCount = document.getElementById('likeCount');

            fetch('{{ route("like.toggle", ["type" => "lectures", "id" => $lecture->id]) }}', {
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
                        likeBtn.style.background = '#dc3545';
                        likeBtn.style.color = 'white';
                    } else {
                        likeIcon.classList.remove('fas');
                        likeIcon.classList.add('far');
                        likeBtn.style.background = 'white';
                        likeBtn.style.color = '#dc3545';
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    favoritable_type: '{{ \App\Models\Lecture::class }}',
                    favoritable_id: {{ $lecture->id }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.is_favorited) {
                        favoriteIcon.classList.remove('far');
                        favoriteIcon.classList.add('fas');
                        favoriteBtn.style.background = '#ffc107';
                        favoriteBtn.style.color = 'white';
                        favoriteBtn.innerHTML = '<i class="fas fa-bookmark" style="margin-left: 8px;"></i> محفوظة';
                    } else {
                        favoriteIcon.classList.remove('fas');
                        favoriteIcon.classList.add('far');
                        favoriteBtn.style.background = 'white';
                        favoriteBtn.style.color = '#ffc107';
                        favoriteBtn.innerHTML = '<i class="far fa-bookmark" style="margin-left: 8px;"></i> حفظ';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الحفظ. حاول مرة أخرى.');
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
            document.getElementById('review').value = '';
            updateStars(0);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('#ratingStars i');
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    selectedRating = parseInt(this.dataset.rating);
                    document.getElementById('ratingValue').value = selectedRating;
                    updateStars(selectedRating);
                });

                star.addEventListener('mouseenter', function() {
                    updateStars(parseInt(this.dataset.rating));
                });
            });

            document.getElementById('ratingStars').addEventListener('mouseleave', function() {
                updateStars(selectedRating);
            });
        });

        function updateStars(rating) {
            const stars = document.querySelectorAll('#ratingStars i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                    star.style.color = '#ffc107';
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
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

            fetch('{{ route("rating.store", ["type" => "lectures", "id" => $lecture->id]) }}', {
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

        @if($lecture->status == 'scheduled' && $lecture->scheduled_at->isFuture())
            // العد التنازلي للمحاضرة
            function updateCountdown() {
                const lectureDate = new Date('{{ $lecture->scheduled_at->toISOString() }}').getTime();
                const now = new Date().getTime();
                const distance = lectureDate - now;

                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    document.getElementById('countdown').innerHTML =
                        days + " يوم " + hours + " ساعة " + minutes + " دقيقة " + seconds + " ثانية";
                } else {
                    document.getElementById('countdown').innerHTML = "بدأت المحاضرة!";
                }
            }

            // تحديث العد التنازلي كل ثانية
            setInterval(updateCountdown, 1000);
            updateCountdown(); // تشغيل فوري
        @endif
    </script>
</body>
</html>
