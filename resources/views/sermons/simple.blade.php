<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الخطب الجاهزة - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sermons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
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
                    <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('sermons.index') }}" class="active"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء</a></li>
                    <li><a href="{{ route('thinkers.index') }}"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
                    <li><a href="{{ route('search.index') }}"><i class="fas fa-search"></i> البحث</a></li>
                    <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container page-content">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-book-open"></i>
                الخطب الجاهزة
            </h1>
            <p class="page-description">
                مجموعة متنوعة من الخطب الجاهزة لمختلف المناسبات والمواضيع
            </p>
        </div>

        <!-- فلاتر البحث -->
        <div class="search-filters">
            <div class="search-box">
                <input type="text" placeholder="ابحث في الخطب..." class="search-input">
                <button class="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-options">
                <select class="filter-select">
                    <option value="">جميع الفئات</option>
                    <option value="aqeedah">العقيدة</option>
                    <option value="fiqh">الفقه</option>
                    <option value="akhlaq">الأخلاق</option>
                    <option value="seerah">السيرة النبوية</option>
                    <option value="occasions">المناسبات</option>
                </select>
                <select class="sort-select">
                    <option value="latest">الأحدث</option>
                    <option value="popular">الأكثر مشاهدة</option>
                    <option value="downloads">الأكثر تحميلاً</option>
                </select>
            </div>
        </div>

        <!-- قائمة الخطب -->
        <div class="sermons-grid">
            @forelse($sermons as $sermon)
                <div class="sermon-card">
                    <div class="sermon-header">
                        <h3 class="sermon-title">{{ $sermon->title }}</h3>
                        <span class="sermon-category">{{ $sermon->category }}</span>
                    </div>
                    <div class="sermon-content">
                        <p class="sermon-excerpt">{{ Str::limit($sermon->content, 150) }}</p>
                        <div class="sermon-meta">
                            <span class="sermon-author">
                                <i class="fas fa-user"></i>
                                {{ $sermon->author->name ?? 'غير محدد' }}
                            </span>
                            <span class="sermon-date">
                                <i class="fas fa-calendar"></i>
                                {{ $sermon->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                        <div class="sermon-stats">
                            <span class="stat-item">
                                <i class="fas fa-eye"></i>
                                {{ $sermon->views_count }} مشاهدة
                            </span>
                            <span class="stat-item">
                                <i class="fas fa-download"></i>
                                {{ $sermon->downloads_count }} تحميل
                            </span>
                        </div>
                    </div>
                    <div class="sermon-actions">
                        <a href="{{ route('sermons.show', $sermon->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                        <a href="{{ route('sermons.download', $sermon->id) }}" class="btn btn-secondary">
                            <i class="fas fa-download"></i>
                            تحميل
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h3>لا توجد خطب متاحة حالياً</h3>
                    <p>سيتم إضافة المزيد من الخطب قريباً</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($sermons->hasPages())
            <div class="pagination-wrapper">
                {{ $sermons->links() }}
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
</body>
</html>
