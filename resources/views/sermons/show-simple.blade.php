@php
use App\Models\Rating;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $sermon->title }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sermons.css') }}">
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
        
        .sermon-header {
            background: white !important;
            padding: 30px !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
            margin-bottom: 30px !important;
            display: block !important;
        }

        .sermon-title {
            color: #1d8a4e !important;
            font-size: 2.5rem !important;
            margin-bottom: 25px !important;
            padding-bottom: 20px !important;
            font-weight: bold !important;
            text-align: center !important;
            border-bottom: 2px solid #f0f0f0 !important;
        }

        .sermon-meta {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 30px !important;
            margin-bottom: 20px !important;
            flex-wrap: wrap !important;
        }
        
        .meta-item {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            color: #666 !important;
            white-space: nowrap !important;
            font-weight: 500 !important;
        }

        .meta-item i {
            color: #1d8a4e !important;
            font-size: 1rem !important;
        }
        
        .sermon-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .sermon-text {
            font-size: 1.2rem;
            line-height: 2;
            text-align: justify;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #1d8a4e;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #155a35;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
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
        
        .tags {
            margin-top: 20px;
        }
        
        .tag {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            margin: 5px 5px 5px 0;
        }
        
        .references {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .reference-item {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* تنسيقات متجاوبة */
        @media (max-width: 768px) {
            .sermon-title {
                font-size: 1.8rem !important;
            }

            .sermon-meta {
                gap: 15px !important;
            }

            .meta-item {
                font-size: 0.9rem !important;
            }
        }

        @media (max-width: 480px) {
            .sermon-title {
                font-size: 1.5rem !important;
            }

            .sermon-meta {
                gap: 10px !important;
            }

            .meta-item {
                font-size: 0.85rem !important;
            }
        }
        
        .reference-item i {
            color: #1d8a4e;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('sermons.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة الخطب
        </a>
        
        <div class="sermon-header">
            <h1 class="sermon-title">{{ $sermon->title }}</h1>
            
            <div class="sermon-meta">
                <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <span>{{ $categories[$sermon->category] ?? $sermon->category }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $sermon->author->name ?? 'غير محدد' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ ($sermon->published_at ?? $sermon->created_at)->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($sermon->views_count) }} مشاهدة</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-download"></i>
                    <span>{{ number_format($sermon->downloads_count) }} تحميل</span>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('sermons.download', $sermon->id) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    تحميل الخطبة
                </a>
                <button class="btn btn-secondary" onclick="shareSermon()">
                    <i class="fas fa-share"></i>
                    مشاركة
                </button>
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    طباعة
                </button>
            </div>
        </div>
        
        <div class="sermon-content">
            <h3 style="color: #1d8a4e; margin-bottom: 20px;">محتوى الخطبة</h3>
            <div class="sermon-text">
                {!! $sermon->content !!}
            </div>
            
            @if($sermon->tags && count($sermon->tags ?? []) > 0)
                <div class="tags">
                    <h4 style="color: #1d8a4e;">الكلمات المفتاحية:</h4>
                    @foreach($sermon->tags ?? [] as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
            
            @if($sermon->references && count($sermon->references ?? []) > 0)
                <div class="references">
                    <h4 style="color: #1d8a4e; margin-bottom: 15px;">المراجع والمصادر:</h4>
                    @foreach($sermon->references ?? [] as $reference)
                        <div class="reference-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $reference }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Like, Favorite and Rating Section -->
            <div class="sermon-actions" style="margin-top: 30px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                    <!-- Like Button -->
                    <div>
                        @auth
                            <button class="btn {{ $sermon->isLikedBy(auth()->user()) ? 'btn-danger' : 'btn-outline-danger' }} btn-lg w-100"
                                    id="likeBtn"
                                    onclick="toggleLike()"
                                    style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #dc3545; background: {{ $sermon->isLikedBy(auth()->user()) ? '#dc3545' : 'white' }}; color: {{ $sermon->isLikedBy(auth()->user()) ? 'white' : '#dc3545' }}; cursor: pointer; transition: all 0.3s;">
                                <i class="{{ $sermon->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart" id="likeIcon" style="margin-left: 8px;"></i>
                                <span id="likeCount">{{ $sermon->likes_count ?? 0 }}</span>
                                إعجاب
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                               style="display: block; width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 8px; border: 2px solid #dc3545; background: white; color: #dc3545; text-align: center; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                                <i class="far fa-heart" style="margin-left: 8px;"></i>
                                <span>{{ $sermon->likes_count ?? 0 }}</span>
                                إعجاب
                            </a>
                        @endauth
                    </div>

                    <!-- Favorite Button -->
                    <div>
                        @auth
                            @php
                                $isFavorited = auth()->user()->favorites()
                                    ->where('favoritable_type', \App\Models\Sermon::class)
                                    ->where('favoritable_id', $sermon->id)
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
                                $avgRating = $sermon->getAverageRating();
                                $ratingsCount = $sermon->getRatingsCount();
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
                                    قيّم الخطبة
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                   style="display: inline-block; padding: 8px 20px; background: #1d8a4e; color: white; border-radius: 5px; text-decoration: none; font-size: 0.95rem;">
                                    <i class="fas fa-star" style="margin-left: 5px;"></i>
                                    قيّم الخطبة
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        @if($relatedSermons->count() > 0)
            <div class="sermon-content">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">خطب ذات صلة</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($relatedSermons as $related)
                        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
                            <h4>
                                <a href="{{ route('sermons.show', $related->id) }}" style="color: #1d8a4e; text-decoration: none;">
                                    {{ $related->title }}
                                </a>
                            </h4>
                            <p style="color: #666; margin: 10px 0;">
                                {{ Str::limit(strip_tags($related->content), 100) }}
                            </p>
                            <small style="color: #999;">
                                بواسطة: {{ $related->author->name ?? 'غير محدد' }} | 
                                {{ $related->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Rating Modal -->
    @auth
    <div id="ratingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #1d8a4e;">تقييم الخطبة</h3>
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

            fetch('{{ route("like.toggle", ["type" => "sermons", "id" => $sermon->id]) }}', {
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

            const requestData = {
                favoritable_type: {!! json_encode(\App\Models\Sermon::class) !!},
                favoritable_id: {{ $sermon->id }}
            };

            console.log('🔖 Sending favorite request:', requestData);

            fetch('{{ route("favorites.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('📡 Response status:', response.status);
                console.log('📡 Response headers:', response.headers.get('content-type'));

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
                        favoriteBtn.style.background = '#ffc107';
                        favoriteBtn.style.color = 'white';
                        favoriteBtn.innerHTML = '<i class="fas fa-bookmark" style="margin-left: 8px;"></i> محفوظة';
                        console.log('✅ تمت الإضافة للمفضلات');
                    } else {
                        favoriteIcon.classList.remove('fas');
                        favoriteIcon.classList.add('far');
                        favoriteBtn.style.background = 'white';
                        favoriteBtn.style.color = '#ffc107';
                        favoriteBtn.innerHTML = '<i class="far fa-bookmark" style="margin-left: 8px;"></i> حفظ';
                        console.log('✅ تمت الإزالة من المفضلات');
                    }
                } else {
                    console.error('❌ فشل الطلب:', data.message);
                    alert(data.message || 'حدث خطأ أثناء الحفظ');
                }
            })
            .catch(error => {
                console.error('❌ Error:', error);
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

            fetch('{{ route("rating.store", ["type" => "sermons", "id" => $sermon->id]) }}', {
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

        function shareSermon() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $sermon->title }}',
                    text: 'اطلع على هذه الخطبة من موقع تمسيك',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('تم نسخ رابط الخطبة');
                }, function() {
                    alert('فشل في نسخ الرابط');
                });
            }
        }
    </script>
</body>
</html>
