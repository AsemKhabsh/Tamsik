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

        <!-- شريط البحث والفلترة -->
        <div class="search-filter-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="search-input" placeholder="ابحث في المقالات...">
            </div>
            <div class="filter-controls">
                <select id="category-filter">
                    <option value="">جميع التصنيفات</option>
                    <option value="فكر-اسلامي">الفكر الإسلامي</option>
                    <option value="دعوة">الدعوة والإرشاد</option>
                    <option value="تربية">التربية الإسلامية</option>
                    <option value="مجتمع">قضايا مجتمعية</option>
                    <option value="شباب">الشباب والأسرة</option>
                    <option value="معاصر">قضايا معاصرة</option>
                </select>
                <select id="sort-filter">
                    <option value="newest">الأحدث</option>
                    <option value="oldest">الأقدم</option>
                    <option value="most-viewed">الأكثر مشاهدة</option>
                    <option value="most-commented">الأكثر تعليقاً</option>
                </select>
            </div>
        </div>

        <!-- قائمة المقالات -->
        <div class="articles-section">
            <div class="articles-grid">
                @foreach($articles as $article)
                <article class="article-card" data-category="{{ $article->category }}">
                    <div class="article-image">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                        @else
                            <div class="article-placeholder">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        @endif
                        <div class="article-category">{{ $article->category }}</div>
                    </div>
                    <div class="article-content">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <p class="article-excerpt">{{ Str::limit($article->content, 150) }}</p>
                        <div class="article-meta">
                            <div class="author-info">
                                <i class="fas fa-user"></i>
                                <span>{{ $article->author->name ?? 'غير محدد' }}</span>
                            </div>
                            <div class="article-date">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $article->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="article-stats">
                            <span class="views-count">
                                <i class="fas fa-eye"></i>
                                {{ number_format($article->views_count ?? 0) }}
                            </span>
                            <span class="comments-count">
                                <i class="fas fa-comments"></i>
                                {{ $article->comments_count ?? 0 }}
                            </span>
                            <span class="reading-time">
                                <i class="fas fa-clock"></i>
                                {{ $article->reading_time ?? 5 }} دقائق
                            </span>
                        </div>
                        <div class="article-actions">
                            <a href="{{ route('thinkers.show', $article->id) }}" class="btn btn-primary">
                                <i class="fas fa-book-open"></i>
                                قراءة المقال
                            </a>
                            <button class="btn btn-outline share-btn" data-article-id="{{ $article->id }}">
                                <i class="fas fa-share"></i>
                                مشاركة
                            </button>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- رسالة عدم وجود مقالات -->
            @if($articles->count() == 0)
            <div class="no-articles">
                <div class="no-articles-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>لا توجد مقالات متاحة حالياً</h3>
                <p>سيتم إضافة المقالات قريباً. تابعونا للحصول على آخر التحديثات.</p>
            </div>
            @endif
        </div>

        <!-- قسم المفكرين المميزين -->
        <div class="featured-thinkers-section">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                المفكرون المميزون
            </h2>
            <div class="thinkers-grid">
                @foreach($featuredThinkers as $thinker)
                <div class="thinker-card">
                    <div class="thinker-image">
                        @if($thinker->image)
                            <img src="{{ asset('storage/' . $thinker->image) }}" alt="{{ $thinker->name }}">
                        @else
                            <div class="thinker-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="thinker-info">
                        <h3 class="thinker-name">{{ $thinker->name }}</h3>
                        <p class="thinker-title">{{ $thinker->title ?? 'مفكر ومؤلف' }}</p>
                        <p class="thinker-bio">{{ Str::limit($thinker->bio, 100) }}</p>
                        <div class="thinker-stats">
                            <span><i class="fas fa-file-alt"></i> {{ $thinker->articles_count ?? 0 }} مقال</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($thinker->total_views ?? 0) }} مشاهدة</span>
                        </div>
                        <a href="{{ route('thinkers.show', $thinker->id) }}" class="btn btn-outline">
                            <i class="fas fa-user"></i>
                            عرض الملف
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- قسم التصنيفات -->
        <div class="categories-section">
            <h2 class="section-title">
                <i class="fas fa-tags"></i>
                التصنيفات
            </h2>
            <div class="categories-grid">
                <div class="category-item" data-category="فكر-اسلامي">
                    <div class="category-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>الفكر الإسلامي</h3>
                    <p>مقالات حول الفكر الإسلامي المعاصر</p>
                    <span class="articles-count">{{ $islamicThoughtCount ?? 0 }} مقال</span>
                </div>
                <div class="category-item" data-category="دعوة">
                    <div class="category-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3>الدعوة والإرشاد</h3>
                    <p>مقالات في الدعوة وأساليبها</p>
                    <span class="articles-count">{{ $dawahCount ?? 0 }} مقال</span>
                </div>
                <div class="category-item" data-category="تربية">
                    <div class="category-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>التربية الإسلامية</h3>
                    <p>مقالات في التربية والتعليم</p>
                    <span class="articles-count">{{ $educationCount ?? 0 }} مقال</span>
                </div>
                <div class="category-item" data-category="مجتمع">
                    <div class="category-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>قضايا مجتمعية</h3>
                    <p>مقالات حول القضايا المجتمعية</p>
                    <span class="articles-count">{{ $societyCount ?? 0 }} مقال</span>
                </div>
                <div class="category-item" data-category="شباب">
                    <div class="category-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>الشباب والأسرة</h3>
                    <p>مقالات موجهة للشباب والأسرة</p>
                    <span class="articles-count">{{ $youthCount ?? 0 }} مقال</span>
                </div>
                <div class="category-item" data-category="معاصر">
                    <div class="category-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>قضايا معاصرة</h3>
                    <p>مقالات حول القضايا المعاصرة</p>
                    <span class="articles-count">{{ $contemporaryCount ?? 0 }} مقال</span>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="quick-stats">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $articles->count() }}</h3>
                        <p>مقال منشور</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $featuredThinkers->count() }}</h3>
                        <p>مفكر ومؤلف</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalViews ?? 0) }}</h3>
                        <p>إجمالي المشاهدات</p>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalComments ?? 0 }}</h3>
                        <p>إجمالي التعليقات</p>
                    </div>
                </div>
            </div>
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
    <script src="{{ asset('js/thinkers.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
</body>
</html>
