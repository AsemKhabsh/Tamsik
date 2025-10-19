<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمسيك - المحاضرات والدروس الدورية</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lectures.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-protection.css') }}">

    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="{{ url('/') }}" class="logo">تمسيك</a>
                <p class="slogan">"والذين يمسكون بالكتاب..."</p>
                <button class="mobile-menu-toggle"><i class="fas fa-bars"></i></button>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('sermons.index') }}"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}" data-auth-required="member"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء اليمنيين</a></li>
                    <li><a href="{{ route('thinkers.index') }}"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}" class="active"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
                </ul>

                <!-- معلومات المستخدم -->
                <div class="user-info" data-auth-only style="display: none;">
                    <span data-user-name></span>
                    <span class="user-role-badge" data-user-role></span>
                    <button class="btn-logout" onclick="window.authProtection?.logout()">
                        <i class="fas fa-sign-out-alt"></i> خروج
                    </button>
                </div>

                <!-- أزرار تسجيل الدخول -->
                <div class="auth-buttons" data-guest-only>
                    <a href="{{ route('login') }}" class="btn btn-outline">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">إنشاء حساب</a>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title">المحاضرات والدروس الدورية</h1>
                    <p class="hero-subtitle">تابع أحدث المحاضرات والدروس الدينية من علماء ومفكري اليمن</p>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $upcomingLectures->count() }}</span>
                            <span class="stat-label">محاضرة قادمة</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $pastLectures->count() }}</span>
                            <span class="stat-label">محاضرة سابقة</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $lectures->count() }}</span>
                            <span class="stat-label">إجمالي المحاضرات</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Filter Section -->
            <section class="filter-section">
                <div class="filter-container">
                    <div class="filter-header">
                        <h3>تصفية المحاضرات</h3>
                        @auth
                            @if(in_array(auth()->user()->user_type, ['preacher', 'scholar']))
                                <div class="lecture-actions-btns">
                                    <a href="{{ route('lectures.my') }}" class="btn btn-outline my-lectures-btn">
                                        <i class="fas fa-list me-2"></i>
                                        محاضراتي
                                    </a>
                                    <a href="{{ route('lectures.create') }}" class="btn btn-primary add-lecture-btn">
                                        <i class="fas fa-plus me-2"></i>
                                        إضافة محاضرة جديدة
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                    <div class="filter-options">
                        <button class="filter-btn active" data-filter="all">جميع المحاضرات</button>
                        <button class="filter-btn" data-filter="upcoming">المحاضرات القادمة</button>
                        <button class="filter-btn" data-filter="past">المحاضرات السابقة</button>
                        <button class="filter-btn" data-filter="live">البث المباشر</button>
                    </div>
                    <div class="search-container">
                        <input type="text" id="lectureSearch" placeholder="ابحث في المحاضرات...">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </section>

            <!-- Upcoming Lectures -->
            @if($upcomingLectures->count() > 0)
            <section class="lectures-section" id="upcoming-lectures">
                <h2 class="section-title">
                    <i class="fas fa-calendar-alt"></i>
                    المحاضرات القادمة
                </h2>
                <div class="lectures-grid">
                    @foreach($upcomingLectures as $lecture)
                    <div class="lecture-card upcoming" data-category="upcoming">
                        <div class="lecture-header">
                            <div class="lecture-status">
                                <span class="status-badge upcoming">قادمة</span>
                                <span class="lecture-date">{{ $lecture->scheduled_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="lecture-time">
                                <i class="fas fa-clock"></i>
                                {{ $lecture->scheduled_at->format('h:i A') }}
                            </div>
                        </div>
                        <div class="lecture-content">
                            <h3 class="lecture-title">{{ $lecture->title }}</h3>
                            <p class="lecture-speaker">
                                <i class="fas fa-user"></i>
                                {{ $lecture->speaker ? $lecture->speaker->name : 'غير محدد' }}
                            </p>
                            <p class="lecture-location">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $lecture->location ?? 'غير محدد' }}
                            </p>
                            @if($lecture->description)
                            <p class="lecture-description">{{ Str::limit($lecture->description, 100) }}</p>
                            @endif
                        </div>
                        <div class="lecture-actions">
                            <a href="{{ route('lectures.show', $lecture->id) }}" class="btn btn-primary">
                                <i class="fas fa-info-circle"></i>
                                التفاصيل
                            </a>
                            <button class="btn btn-outline" onclick="addToCalendar({{ $lecture->id }})">
                                <i class="fas fa-calendar-plus"></i>
                                إضافة للتقويم
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- Past Lectures -->
            @if($pastLectures->count() > 0)
            <section class="lectures-section" id="past-lectures">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    المحاضرات السابقة
                </h2>
                <div class="lectures-grid">
                    @foreach($pastLectures as $lecture)
                    <div class="lecture-card past" data-category="past">
                        <div class="lecture-header">
                            <div class="lecture-status">
                                <span class="status-badge completed">مكتملة</span>
                                <span class="lecture-date">{{ $lecture->scheduled_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="lecture-views">
                                <i class="fas fa-eye"></i>
                                {{ number_format($lecture->views_count ?? 0) }}
                            </div>
                        </div>
                        <div class="lecture-content">
                            <h3 class="lecture-title">{{ $lecture->title }}</h3>
                            <p class="lecture-speaker">
                                <i class="fas fa-user"></i>
                                {{ $lecture->speaker ? $lecture->speaker->name : 'غير محدد' }}
                            </p>
                            @if($lecture->description)
                            <p class="lecture-description">{{ Str::limit($lecture->description, 100) }}</p>
                            @endif
                        </div>
                        <div class="lecture-actions">
                            <a href="{{ route('lectures.show', $lecture->id) }}" class="btn btn-primary">
                                <i class="fas fa-play"></i>
                                مشاهدة
                            </a>
                            @if($lecture->audio_file)
                            <a href="{{ asset('storage/' . $lecture->audio_file) }}" class="btn btn-outline" download>
                                <i class="fas fa-download"></i>
                                تحميل
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            <!-- No Lectures Message -->
            @if($lectures->count() == 0)
            <section class="no-content">
                <div class="no-content-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3>لا توجد محاضرات متاحة حالياً</h3>
                <p>سيتم إضافة المحاضرات قريباً. تابعونا للحصول على آخر التحديثات.</p>
            </section>
            @endif

            <!-- Newsletter Subscription -->
            <section class="newsletter-section">
                <div class="newsletter-content">
                    <h3>اشترك في النشرة الإخبارية</h3>
                    <p>احصل على إشعارات بالمحاضرات الجديدة والأحداث القادمة</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                        <button type="submit" class="btn btn-primary">اشتراك</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3>عن تمسيك</h3>
                    <p>منصة إسلامية شاملة تهدف إلى مساعدة الخطباء والباحثين وعامة المسلمين في الوصول إلى محتوى إسلامي موثوق.</p>
                </div>
                <div class="footer-links">
                    <h3>روابط سريعة</h3>
                    <ul>
                        <li><a href="{{ url('/') }}">الرئيسية</a></li>
                        <li><a href="{{ route('sermons.index') }}">الخطب</a></li>
                        <li><a href="{{ route('scholars.index') }}">العلماء</a></li>
                        <li><a href="{{ route('lectures.index') }}">المحاضرات</a></li>
                        <li><a href="{{ route('thinkers.index') }}">المفكرون</a></li>
                        <li><a href="{{ route('search.index') }}">البحث</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h3>تواصل معنا</h3>
                    <p><i class="fas fa-envelope"></i> info@tamsik.com</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 تمسيك. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/lectures.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
    
    <style>
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .lecture-actions-btns {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .add-lecture-btn {
            background: linear-gradient(135deg, #2c5aa0 0%, #1e3a8a 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(44, 90, 160, 0.3);
        }

        .add-lecture-btn:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 90, 160, 0.4);
            color: white;
            text-decoration: none;
        }

        .my-lectures-btn {
            background: transparent;
            color: #2c5aa0;
            padding: 12px 24px;
            border: 2px solid #2c5aa0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .my-lectures-btn:hover {
            background: #2c5aa0;
            color: white;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .add-lecture-btn i,
        .my-lectures-btn i {
            margin-left: 8px;
        }

        @media (max-width: 768px) {
            .filter-header {
                flex-direction: column;
                align-items: stretch;
            }

            .lecture-actions-btns {
                flex-direction: column;
                width: 100%;
            }

            .add-lecture-btn,
            .my-lectures-btn {
                text-align: center;
                width: 100%;
            }
        }
    </style>

    <script>
        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.lecture-card');
                
                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('lectureSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.lecture-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.lecture-title').textContent.toLowerCase();
                const speaker = card.querySelector('.lecture-speaker').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || speaker.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Add to calendar function
        function addToCalendar(lectureId) {
            // Implementation for adding to calendar
            alert('سيتم إضافة المحاضرة إلى التقويم قريباً');
        }
    </script>
</body>
</html>
