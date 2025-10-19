<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمسك - منصة إسلامية</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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
                <button class="mobile-menu-toggle" title="فتح القائمة"><i class="fas fa-bars"></i></button>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}" class="active"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('sermons.index') }}"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء</a></li>
                    <li><a href="{{ route('thinkers.index') }}"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
                    <li><a href="{{ route('search.index') }}"><i class="fas fa-search"></i> البحث</a></li>
                    <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                    <li><a href="{{ route('register') }}"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة الإدارة</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="hero-section">
        <div class="hero-background"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    <span>منصة إسلامية موثوقة</span>
                </div>
                <h1 class="hero-title">
                    <span class="highlight">تمسيك</span> - منصة العلماء اليمنيين
                </h1>
                <p class="hero-description">
                    منصة متكاملة تجمع بين الخطب الجاهزة، وأدوات إعداد الخطب، وفتاوى العلماء اليمنيين،
                    ومحاضرات الدعاة في جميع محافظات اليمن السعيد
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-count="{{ $stats['sermons_count'] }}">{{ $stats['sermons_count'] }}</span>
                        <span class="stat-label">خطبة جاهزة</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="{{ $stats['scholars_count'] }}">{{ $stats['scholars_count'] }}</span>
                        <span class="stat-label">عالم يمني</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="1000">1000</span>
                        <span class="stat-label">فتوى شرعية</span>
                    </div>
                </div>
                <div class="cta-buttons">
                    <a href="#" class="btn btn-primary btn-large">
                        <i class="fas fa-pen"></i>
                        ابدأ في إعداد خطبة
                    </a>
                    <a href="#" class="btn btn-secondary btn-large">
                        <i class="fas fa-book-open"></i>
                        تصفح الخطب الجاهزة
                    </a>
                </div>
                <div class="hero-features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>محتوى موثوق ومراجع</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>سهولة في الاستخدام</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>تحديث مستمر</span>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="image-container">
                    <img src="{{ asset('images/musq.webp') }}" alt="صورة مسجد" class="img-responsive">
                    <div class="image-overlay">
                        <div class="floating-card">
                            <i class="fas fa-mosque"></i>
                            <h4>أكثر من {{ $stats['sermons_count'] }} خطبة</h4>
                            <p>جاهزة للاستخدام</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="container page-content">
        <!-- قسم الميزات -->
        <section class="features-section">
            <h2 class="section-title">ما يميزنا</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-mosque feature-icon"></i>
                    <h3>خطب جمعة متنوعة</h3>
                    <p>مجموعة متنوعة من خطب الجمعة الجاهزة في مختلف المواضيع</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-edit feature-icon"></i>
                    <h3>إعداد خطب مخصصة</h3>
                    <p>أدوات مساعدة لإعداد خطب مخصصة بسهولة وفعالية</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users feature-icon"></i>
                    <h3>آراء العلماء اليمنيين</h3>
                    <p>مجموعة من فتاوى وآراء كبار العلماء اليمنيين في مختلف القضايا الشرعية</p>
                </div>
            </div>
        </section>

        <!-- قسم أقسام الموقع -->
        <section class="site-sections">
            <h2 class="section-title">أقسام الموقع</h2>
            <div class="sections-grid">
                <a href="{{ route('sermons.index') }}" class="section-card">
                    <i class="fas fa-book-open section-icon"></i>
                    <h3>الخطب الجاهزة</h3>
                    <p>تصفح مجموعة متنوعة من الخطب الجاهزة</p>
                </a>
                <a href="#" class="section-card">
                    <i class="fas fa-pen section-icon"></i>
                    <h3>إعداد خطبة</h3>
                    <p>ابدأ في إعداد خطبتك الخاصة</p>
                </a>
                <a href="{{ route('scholars.index') }}" class="section-card">
                    <i class="fas fa-user-graduate section-icon"></i>
                    <h3>العلماء اليمنيين</h3>
                    <p>اطلع على فتاوى وآراء كبار العلماء اليمنيين</p>
                </a>
                <a href="{{ route('thinkers.index') }}" class="section-card">
                    <i class="fas fa-lightbulb section-icon"></i>
                    <h3>المفكرون والدعاة</h3>
                    <p>اقرأ مقالات المفكرين والدعاة المعاصرين</p>
                </a>
                <a href="{{ route('lectures.index') }}" class="section-card">
                    <i class="fas fa-microphone section-icon"></i>
                    <h3>المحاضرات والدروس</h3>
                    <p>اعرف مواعيد المحاضرات والدروس في محافظات اليمن</p>
                </a>
            </div>
        </section>

        <!-- قسم الإحصائيات -->
        <section class="stats-section">
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number" data-count="{{ $stats['sermons_count'] }}">{{ $stats['sermons_count'] }}</h3>
                        <p class="stat-label">خطبة جاهزة</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number" data-count="{{ $stats['scholars_count'] }}">{{ $stats['scholars_count'] }}</h3>
                        <p class="stat-label">عالم يمني</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number" data-count="1000">1000</h3>
                        <p class="stat-label">فتوى شرعية</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-number" data-count="{{ $stats['users_count'] }}">{{ $stats['users_count'] }}</h3>
                        <p class="stat-label">مستخدم نشط</p>
                    </div>
                </div>
            </div>
        </section>
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
                        <li><a href="{{ route('sermons.index') }}">الخطب</a></li>
                        <li><a href="{{ route('scholars.index') }}">العلماء</a></li>
                        <li><a href="{{ route('lectures.index') }}">المحاضرات</a></li>
                        <li><a href="{{ route('thinkers.index') }}">المفكرون</a></li>
                        <li><a href="{{ route('search.index') }}">البحث</a></li>
                        <li><a href="{{ route('sermons.create') }}">إعداد خطبة</a></li>
                        <li><a href="{{ route('login') }}">تسجيل الدخول</a></li>
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
</body>
</html>
