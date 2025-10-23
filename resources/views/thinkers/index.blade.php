<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمسيك - المفكرون والدعاة</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thinkers.css') }}">
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
                    <li><a href="{{ route('thinkers.index') }}" class="active"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
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

    <main class="container page-content">
        <!-- عنوان الصفحة -->
        <div class="page-header">
            <div class="page-title-section">
                <h1 class="page-title">
                    <i class="fas fa-lightbulb"></i>
                    المفكرون والدعاة
                </h1>
                <p class="page-description">
                    اقرأ مقالات وأفكار المفكرين والدعاة المعاصرين في مختلف القضايا الإسلامية والمجتمعية
                </p>
            </div>
            <div class="page-actions">
                <button id="add-article-btn" class="btn btn-primary" style="display: none;">
                    <i class="fas fa-plus"></i>
                    إضافة مقال جديد
                </button>
            </div>
        </div>

        <!-- المفكرون المميزون -->
        <div class="thinkers-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                المفكرون المميزون
            </h2>
            <div class="thinkers-grid">
                @forelse($thinkers as $thinker)
                    <div class="thinker-card">
                        <div class="thinker-avatar">
                            @if($thinker->avatar)
                                <img src="{{ asset('storage/' . $thinker->avatar) }}" alt="{{ $thinker->name }}">
                            @elseif($thinker->image)
                                <img src="{{ asset('storage/' . $thinker->image) }}" alt="{{ $thinker->name }}">
                            @else
                                <i class="fas fa-user-tie"></i>
                            @endif
                        </div>
                        <div class="thinker-info">
                            <h3 class="thinker-name">{{ $thinker->name }}</h3>
                            <p class="thinker-title">{{ $thinker->title ?? 'مفكر ومؤلف' }}</p>
                            @if($thinker->bio)
                                <p class="thinker-bio">{{ Str::limit($thinker->bio, 100) }}</p>
                            @endif
                            <div class="thinker-stats">
                                <div class="stat-item">
                                    <i class="fas fa-newspaper"></i>
                                    <span>{{ $thinker->articles_count ?? 0 }} مقال</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-eye"></i>
                                    <span>{{ $thinker->total_views ?? 0 }} مشاهدة</span>
                                </div>
                            </div>
                            <a href="{{ route('thinkers.show', $thinker->id) }}" class="btn btn-primary">
                                <i class="fas fa-user-circle"></i>
                                عرض الملف الشخصي
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-lightbulb"></i>
                        <h3>لا يوجد مفكرون متاحون حالياً</h3>
                        <p>سيتم إضافة المزيد من المفكرين والدعاة قريباً</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($thinkers->hasPages())
            <div class="pagination-wrapper">
                {{ $thinkers->links() }}
            </div>
        @endif
    </main>

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
                        <li><a href="{{ route('sermons.index') }}">الخطب الجاهزة</a></li>
                        <li><a href="{{ route('scholars.index') }}">العلماء</a></li>
                        <li><a href="{{ route('thinkers.index') }}">المفكرون</a></li>
                        <li><a href="{{ route('lectures.index') }}">المحاضرات</a></li>
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

    <!-- JavaScript Files -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
</body>
</html>
