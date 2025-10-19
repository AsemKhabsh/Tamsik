<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>العلماء اليمنيين والفتاوى - تمسيك</title>

    <!-- ملفات CSS -->
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-protection.css') }}">

    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
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
                    <li><a href="{{ route('scholars.index') }}" class="active"><i class="fas fa-user-graduate"></i> العلماء اليمنيين</a></li>
                    <li><a href="{{ route('sermons.index') }}"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}" data-auth-required="member"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('thinkers.index') }}"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
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

    <div class="page-header">
        <div class="container">
            <h1>العلماء اليمنيين والفتاوى</h1>
            <p>منصة للاطلاع على فتاوى العلماء اليمنيين المعتمدين وطرح أسئلتكم الشرعية</p>
        </div>
    </div>

    <main class="container page-content">
        <!-- قسم البحث والتصفية -->
        <section class="search-filter-section">
            <div class="search-box">
                <input type="text" id="fatwa-search" placeholder="ابحث عن فتوى...">
                <button id="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-options">
                <div class="filter-group">
                    <label for="category-filter">التصنيف:</label>
                    <select id="category-filter">
                        <option value="all">جميع التصنيفات</option>
                        <option value="worship">العبادات</option>
                        <option value="transactions">المعاملات</option>
                        <option value="family">الأسرة والزواج</option>
                        <option value="contemporary">قضايا معاصرة</option>
                        <option value="ethics">الأخلاق والآداب</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sort-fatwas">ترتيب حسب:</label>
                    <select id="sort-fatwas">
                        <option value="newest">الأحدث</option>
                        <option value="oldest">الأقدم</option>
                        <option value="most-viewed">الأكثر مشاهدة</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- قسم طرح سؤال جديد -->
        <section class="ask-question-section">
            <div class="ask-question-card">
                <h2><i class="fas fa-question-circle"></i> طرح سؤال جديد</h2>
                <p>يمكنك طرح سؤالك الشرعي ليتم الإجابة عليه من قبل العلماء المختصين</p>
                <button id="ask-question-btn-main" class="btn btn-primary">اطرح سؤالاً</button>
                <div class="login-note">
                    <p><i class="fas fa-info-circle"></i> يجب تسجيل الدخول أولاً للتمكن من طرح سؤال</p>
                    <div class="login-buttons">
                        <a href="{{ route('login') }}" class="btn btn-secondary">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="btn btn-outline">إنشاء حساب</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- قسم العلماء -->
        <section class="scholars-section">
            <h2><i class="fas fa-users"></i> العلماء المعتمدون</h2>
            <div class="scholars-grid">
                @foreach($scholars as $scholar)
                <div class="scholar-card">
                    <div class="scholar-image">
                        @if($scholar->image)
                            <img src="{{ asset('storage/' . $scholar->image) }}" alt="{{ $scholar->name }}">
                        @else
                            <div class="scholar-placeholder">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        @endif
                    </div>
                    <div class="scholar-info">
                        <h3>{{ $scholar->name }}</h3>
                        <p class="scholar-title">{{ $scholar->title ?? 'عالم' }}</p>
                        <p class="scholar-bio">{{ Str::limit($scholar->bio, 100) }}</p>
                        <div class="scholar-stats">
                            <span><i class="fas fa-book"></i> {{ $scholar->fatwas_count ?? 0 }} فتوى</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($scholar->views_count ?? 0) }} مشاهدة</span>
                        </div>
                        <a href="{{ route('scholars.show', $scholar->id) }}" class="btn btn-primary">عرض الملف</a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- قسم الفتاوى الحديثة -->
        <section class="recent-fatwas-section">
            <h2><i class="fas fa-scroll"></i> الفتاوى الحديثة</h2>
            <div class="fatwas-list">
                @foreach($recentFatwas as $fatwa)
                <div class="fatwa-card" data-category="{{ $fatwa->category }}">
                    <div class="fatwa-header">
                        <h3>{{ $fatwa->question }}</h3>
                        <div class="fatwa-meta">
                            <span class="fatwa-category">{{ $fatwa->category }}</span>
                            <span class="fatwa-date">{{ $fatwa->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="fatwa-content">
                        <p class="fatwa-answer">{{ Str::limit($fatwa->answer, 200) }}</p>
                        <div class="fatwa-scholar">
                            <i class="fas fa-user-graduate"></i>
                            <span>{{ $fatwa->scholar->name ?? 'غير محدد' }}</span>
                        </div>
                    </div>
                    <div class="fatwa-actions">
                        <button class="btn btn-outline fatwa-read-more" data-fatwa-id="{{ $fatwa->id }}">
                            <i class="fas fa-eye"></i> قراءة كاملة
                        </button>
                        <button class="btn btn-outline fatwa-share" data-fatwa-id="{{ $fatwa->id }}">
                            <i class="fas fa-share"></i> مشاركة
                        </button>
                        <span class="fatwa-views">
                            <i class="fas fa-eye"></i> {{ number_format($fatwa->views_count ?? 0) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- قسم التصنيفات الشائعة -->
        <section class="popular-categories-section">
            <h2><i class="fas fa-tags"></i> التصنيفات الشائعة</h2>
            <div class="categories-grid">
                <div class="category-card" data-category="worship">
                    <div class="category-icon">
                        <i class="fas fa-pray"></i>
                    </div>
                    <h3>العبادات</h3>
                    <p>فتاوى متعلقة بالصلاة والصيام والحج والزكاة</p>
                    <span class="category-count">{{ $worshipFatwasCount ?? 0 }} فتوى</span>
                </div>
                <div class="category-card" data-category="transactions">
                    <div class="category-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>المعاملات</h3>
                    <p>فتاوى متعلقة بالبيع والشراء والتجارة</p>
                    <span class="category-count">{{ $transactionsFatwasCount ?? 0 }} فتوى</span>
                </div>
                <div class="category-card" data-category="family">
                    <div class="category-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>الأسرة والزواج</h3>
                    <p>فتاوى متعلقة بالزواج والطلاق وتربية الأطفال</p>
                    <span class="category-count">{{ $familyFatwasCount ?? 0 }} فتوى</span>
                </div>
                <div class="category-card" data-category="contemporary">
                    <div class="category-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>قضايا معاصرة</h3>
                    <p>فتاوى متعلقة بالقضايا الحديثة والتكنولوجيا</p>
                    <span class="category-count">{{ $contemporaryFatwasCount ?? 0 }} فتوى</span>
                </div>
                <div class="category-card" data-category="ethics">
                    <div class="category-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>الأخلاق والآداب</h3>
                    <p>فتاوى متعلقة بالأخلاق الإسلامية والآداب</p>
                    <span class="category-count">{{ $ethicsFatwasCount ?? 0 }} فتوى</span>
                </div>
            </div>
        </section>

        <!-- إحصائيات سريعة -->
        <section class="quick-stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $scholars->count() }}</h3>
                        <p>عالم معتمد</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalFatwas ?? 0 }}</h3>
                        <p>فتوى منشورة</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $pendingQuestions ?? 0 }}</h3>
                        <p>سؤال في الانتظار</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $activeUsers ?? 0 }}</h3>
                        <p>مستخدم نشط</p>
                    </div>
                </div>
            </div>
        </section>
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
    <script src="{{ asset('js/scholars.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
</body>
</html>
