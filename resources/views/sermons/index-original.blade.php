<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تمسيك - الخطب الجاهزة</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sermons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-protection.css') }}">
    <link rel="stylesheet" href="{{ asset('css/error-handler.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <!-- إضافة زر القائمة المنسدلة بعد شعار الموقع -->
            <div class="container">
                <a href="{{ url('/') }}" class="logo">تمسيك</a>
                <p class="slogan">"والذين يمسكون بالكتاب..."</p>
                <button class="mobile-menu-toggle" title="فتح القائمة"><i class="fas fa-bars"></i></button>
                <ul class="nav-links">
                    <li><a href="{{ url('/') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('sermons.index') }}" class="active"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="{{ route('sermons.create') }}" data-auth-required="member"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء اليمنيين</a></li>
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
            <h1>الخطب الجاهزة</h1>
            <p>مجموعة شاملة من الخطب الجاهزة لمختلف المناسبات والمواضيع الإسلامية</p>
        </div>
    </div>

    <main class="container page-content">
        <!-- قسم البحث والتصفية -->
        <section class="search-filter-section">
            <div class="search-box">
                <input type="text" id="sermon-search" placeholder="ابحث في الخطب...">
                <button id="search-btn"><i class="fas fa-search"></i></button>
            </div>
            <div class="filter-options">
                <div class="filter-group">
                    <label for="category-filter">التصنيف:</label>
                    <select id="category-filter">
                        <option value="all">جميع التصنيفات</option>
                        <option value="aqeedah">العقيدة</option>
                        <option value="fiqh">الفقه</option>
                        <option value="akhlaq">الأخلاق</option>
                        <option value="seerah">السيرة النبوية</option>
                        <option value="occasions">المناسبات</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="sort-sermons">ترتيب حسب:</label>
                    <select id="sort-sermons">
                        <option value="newest">الأحدث</option>
                        <option value="oldest">الأقدم</option>
                        <option value="most-viewed">الأكثر مشاهدة</option>
                        <option value="most-downloaded">الأكثر تحميلاً</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- قسم إضافة خطبة جديدة -->
        <section class="add-sermon-section">
            <!-- للأعضاء المخولين -->
            <div class="add-sermon-card" id="add-sermon-authorized" style="display: none;">
                <h2><i class="fas fa-plus-circle"></i> إضافة خطبة جديدة</h2>
                <p>يمكنك المساهمة بإضافة خطبة جديدة لمساعدة إخوانك الخطباء</p>
                <a href="{{ route('sermons.create') }}" class="btn btn-primary">إضافة خطبة</a>
            </div>

            <!-- للأعضاء غير المخولين -->
            <div class="add-sermon-card" id="add-sermon-unauthorized" style="display: none;">
                <h2><i class="fas fa-info-circle"></i> إضافة خطبة جديدة</h2>
                <p>إضافة الخطب متاحة للخطباء والعلماء ومشرفي المنصة فقط</p>
                <div class="permission-info">
                    <p><i class="fas fa-user-check"></i> للحصول على صلاحية إضافة الخطب، يرجى التواصل مع إدارة الموقع</p>
                </div>
            </div>

            <!-- للزوار غير المسجلين -->
            <div class="add-sermon-card" id="add-sermon-guest">
                <h2><i class="fas fa-plus-circle"></i> إضافة خطبة جديدة</h2>
                <p>يمكنك المساهمة بإضافة خطبة جديدة لمساعدة إخوانك الخطباء</p>
                <div class="login-note">
                    <p><i class="fas fa-info-circle"></i> يجب تسجيل الدخول أولاً للتمكن من إضافة خطبة</p>
                    <div class="login-buttons">
                        <a href="{{ route('login') }}" class="btn btn-secondary">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="btn btn-outline">إنشاء حساب</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- قسم الخطب المميزة -->
        <section class="featured-sermons">
            <h2 class="section-title">الخطب المميزة</h2>
            <div class="sermons-grid featured-sermons-grid">
                @if($featuredSermons->count() > 0)
                    @foreach($featuredSermons as $sermon)
                    <div class="sermon-card featured">
                        <div class="sermon-header">
                            <h3 class="sermon-title">{{ $sermon->title }}</h3>
                            <span class="sermon-category">{{ $sermon->category }}</span>
                        </div>
                        <div class="sermon-content">
                            <p class="sermon-excerpt">{{ Str::limit($sermon->introduction, 100) }}</p>
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
                                <span class="views">
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($sermon->views_count ?? 0) }}
                                </span>
                                <span class="downloads">
                                    <i class="fas fa-download"></i>
                                    {{ number_format($sermon->downloads_count ?? 0) }}
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
                    @endforeach
                @else
                    <div class="empty-state" id="featured-empty-state">
                        <i class="fas fa-star"></i>
                        <h3>لا توجد خطب مميزة حالياً</h3>
                        <p>سيتم عرض الخطب المميزة هنا عند إضافتها</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- قسم جميع الخطب -->
        <section class="all-sermons">
            <h2 class="section-title">جميع الخطب</h2>

            <!-- تصنيفات الخطب -->
            <div class="sermon-categories">
                <button type="button" class="category-btn active" data-category="all">الكل</button>
                <button type="button" class="category-btn" data-category="aqeedah">العقيدة</button>
                <button type="button" class="category-btn" data-category="fiqh">الفقه</button>
                <button type="button" class="category-btn" data-category="akhlaq">الأخلاق</button>
                <button type="button" class="category-btn" data-category="seerah">السيرة النبوية</button>
                <button type="button" class="category-btn" data-category="occasions">المناسبات</button>
            </div>

            <div class="sermons-grid all-sermons-grid">
                @if($sermons->count() > 0)
                    @foreach($sermons as $sermon)
                    <div class="sermon-card" data-category="{{ $sermon->category }}">
                        <div class="sermon-header">
                            <h3 class="sermon-title">{{ $sermon->title }}</h3>
                            <span class="sermon-category">{{ $sermon->category }}</span>
                        </div>
                        <div class="sermon-content">
                            <p class="sermon-excerpt">{{ Str::limit($sermon->introduction, 100) }}</p>
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
                                <span class="views">
                                    <i class="fas fa-eye"></i>
                                    {{ number_format($sermon->views_count ?? 0) }}
                                </span>
                                <span class="downloads">
                                    <i class="fas fa-download"></i>
                                    {{ number_format($sermon->downloads_count ?? 0) }}
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
                    @endforeach
                @else
                    <div class="empty-state" id="all-sermons-empty-state">
                        <i class="fas fa-book-open"></i>
                        <h3>لا توجد خطب حالياً</h3>
                        <p>سيتم عرض الخطب هنا عند إضافتها</p>
                    </div>
                @endif
            </div>

            <!-- أزرار التنقل بين الصفحات -->
            @if($sermons->count() > 0)
            <div class="pagination">
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">5</a>
                <a href="#" class="next">التالي <i class="fas fa-chevron-left"></i></a>
            </div>
            @endif
        </section>
    </main>

    <section class="newsletter-section">
        <div class="container">
            <h2>اشترك في نشرتنا البريدية</h2>
            <p>احصل على آخر الخطب والمحاضرات مباشرة في بريدك الإلكتروني</p>
            <form class="newsletter-form">
                <input type="email" placeholder="أدخل بريدك الإلكتروني" required>
                <button type="submit" class="btn btn-primary">اشتراك</button>
            </form>
        </div>
    </section>

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
                        <li><a href="{{ route('sermons.create') }}">إعداد خطبة</a></li>
                        <li><a href="{{ route('scholars.index') }}">العلماء</a></li>
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

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/sermons.js') }}"></script>
    <script src="{{ asset('js/auth-protection.js') }}"></script>
    <script src="{{ asset('js/error-handler.js') }}"></script>
</body>
</html>
