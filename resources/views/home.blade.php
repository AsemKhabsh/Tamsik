@extends('layouts.app')

@section('title', 'تمسيك - منصة إسلامية شاملة')

@section('content')
<style>
/* إصلاح عرض أقسام الموقع */
.website-sections .row {
    display: flex !important;
    flex-wrap: wrap !important;
}

.website-sections .col-xl-4,
.website-sections .col-lg-6,
.website-sections .col-md-6 {
    flex: 0 0 auto !important;
}

@media (min-width: 768px) {
    .website-sections .col-md-6 {
        width: 50% !important;
    }
}

@media (min-width: 992px) {
    .website-sections .col-lg-6 {
        width: 50% !important;
    }
}

@media (min-width: 1200px) {
    .website-sections .col-xl-4 {
        width: 33.333333% !important;
    }
}
</style>
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-badge animate-fade-in">
                    <i class="fas fa-star"></i>
                    <span>منصة إسلامية موثوقة</span>
                </div>
                <h1 class="hero-title animate-slide-up">
                    مرحباً بكم في منصة <span class="highlight">تمسيك</span>
                </h1>
                <p class="hero-description animate-slide-up" style="animation-delay: 0.2s;">
                    منصة إسلامية شاملة تجمع خطب وفتاوى ومحاضرات علماء اليمن الأجلاء
                </p>

                <!-- Hero Stats -->
                <div class="hero-stats animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['sermons'] ?? 0 }}+</span>
                        <span class="stat-label">خطبة</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['lectures'] ?? 0 }}+</span>
                        <span class="stat-label">محاضرة</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['scholars'] ?? 0 }}+</span>
                        <span class="stat-label">عالم</span>
                    </div>
                </div>

                <!-- Enhanced Search Box -->
                <div class="enhanced-search-box animate-slide-up" style="animation-delay: 0.6s;">
                    <form action="#" method="GET" class="search-form">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="q" class="search-input"
                                   placeholder="ابحث في الخطب والفتاوى والمحاضرات..."
                                   value="{{ request('q') }}">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CTA Buttons -->
                <div class="cta-buttons animate-slide-up" style="animation-delay: 0.8s;">
                    <a href="{{ route('sermons.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-microphone me-2"></i>
                        تصفح الخطب
                    </a>
                    <a href="{{ route('lectures.index') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        المحاضرات
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual animate-float">
                    <div class="mosque-illustration">
                        <div class="mosque-dome"></div>
                        <div class="mosque-minaret"></div>
                        <div class="mosque-base"></div>
                        <div class="floating-elements">
                            <div class="floating-icon" style="animation-delay: 0s;">
                                <i class="fas fa-quran"></i>
                            </div>
                            <div class="floating-icon" style="animation-delay: 1s;">
                                <i class="fas fa-pray"></i>
                            </div>
                            <div class="floating-icon" style="animation-delay: 2s;">
                                <i class="fas fa-moon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Statistics Section -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="enhanced-stats-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number" data-count="{{ $stats['sermons'] ?? 0 }}">0</div>
                        <h5 class="stats-title">خطبة</h5>
                        <p class="stats-description">خطب متنوعة ومفيدة</p>
                    </div>
                    <div class="stats-decoration"></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="enhanced-stats-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number" data-count="{{ $stats['lectures'] ?? 0 }}">0</div>
                        <h5 class="stats-title">محاضرة</h5>
                        <p class="stats-description">محاضرات علمية قيمة</p>
                    </div>
                    <div class="stats-decoration"></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="enhanced-stats-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stats-content">
                        <div class="stats-number" data-count="{{ $stats['scholars'] ?? 0 }}">0</div>
                        <h5 class="stats-title">عالم</h5>
                        <p class="stats-description">علماء أجلاء</p>
                    </div>
                    <div class="stats-decoration"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Website Sections -->
<section class="website-sections py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-badge">
                <i class="fas fa-sitemap"></i>
                <span>استكشف المحتوى</span>
            </div>
            <h2 class="section-title">أقسام الموقع</h2>
            <p class="section-subtitle">تصفح مختلف أقسام المنصة واكتشف ثروة من المحتوى الإسلامي المتنوع</p>
        </div>

        <div class="row g-4">
            <!-- Sermons Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card sermons-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-microphone"></i>
                        </div>
                        <div class="section-badge-corner">
                            <span>{{ $stats['sermons'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">الخطب</h4>
                        <p class="section-card-description">
                            مجموعة واسعة من الخطب الإسلامية المتنوعة من علماء وخطباء معتبرين، تغطي مختلف المواضيع الدينية والاجتماعية
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-download"></i>
                                قابلة للتحميل
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-search"></i>
                                بحث متقدم
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('sermons.index') }}" class="section-btn">
                            <span>تصفح الخطب</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- Prepare Sermon Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card prepare-sermon-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="section-badge-corner special">
                            <i class="fas fa-tools"></i>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">إعداد الخطب</h4>
                        <p class="section-card-description">
                            أداة متقدمة لإعداد وتنظيم الخطب بشكل احترافي، مع إمكانية الحفظ والطباعة والمشاركة
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-magic"></i>
                                أدوات ذكية
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-save"></i>
                                حفظ تلقائي
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        @auth
                            @if(in_array(auth()->user()->user_type, ['admin', 'preacher', 'scholar']))
                                <a href="{{ route('sermon.prepare') }}" class="section-btn">
                                    <span>ابدأ الإعداد</span>
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            @else
                                <span class="section-btn disabled" title="متاح للخطباء والعلماء فقط">
                                    <span>متاح للخطباء فقط</span>
                                    <i class="fas fa-lock"></i>
                                </span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="section-btn">
                                <span>سجل دخولك للإعداد</span>
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        @endauth
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- Lectures Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card lectures-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="section-badge-corner">
                            <span>{{ $stats['lectures'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">المحاضرات</h4>
                        <p class="section-card-description">
                            محاضرات علمية وتربوية من نخبة من العلماء والمحاضرين، تشمل دروس في العقيدة والفقه والسيرة النبوية
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-calendar"></i>
                                مجدولة
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-users"></i>
                                تفاعلية
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('lectures.index') }}" class="section-btn">
                            <span>تصفح المحاضرات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- Scholars Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card scholars-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="section-badge-corner">
                            <span>{{ $stats['scholars'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">العلماء</h4>
                        <p class="section-card-description">
                            تعرف على نخبة من العلماء والدعاة المعاصرين، واطلع على سيرهم الذاتية ومؤلفاتهم وإنجازاتهم العلمية
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-book"></i>
                                سير ذاتية
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-award"></i>
                                معتمدون
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('scholars.index') }}" class="section-btn">
                            <span>تصفح العلماء</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- Thinkers Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card thinkers-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-pen-fancy"></i>
                        </div>
                        <div class="section-badge-corner">
                            <span>{{ $stats['articles'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">المفكرون</h4>
                        <p class="section-card-description">
                            مقالات فكرية وتحليلية من كتاب ومفكرين إسلاميين، تتناول القضايا المعاصرة من منظور إسلامي أصيل
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-lightbulb"></i>
                                فكرية
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-comments"></i>
                                تحليلية
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('thinkers.index') }}" class="section-btn">
                            <span>تصفح المقالات</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card search-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="section-badge-corner special">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">البحث المتقدم</h4>
                        <p class="section-card-description">
                            ابحث في جميع محتويات المنصة بسهولة وسرعة، مع إمكانية الفلترة حسب النوع والموضوع والمؤلف
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-filter"></i>
                                فلترة ذكية
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-bolt"></i>
                                نتائج سريعة
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('search.index') }}" class="section-btn">
                            <span>البحث المتقدم</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>

            <!-- About Section -->
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                <div class="section-card about-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="section-card-header">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="section-badge-corner special">
                            <i class="fas fa-heart"></i>
                        </div>
                    </div>
                    <div class="section-card-body">
                        <h4 class="section-card-title">عن المنصة</h4>
                        <p class="section-card-description">
                            تعرف على رؤية ورسالة منصة تمسيك، وأهدافها في نشر العلم الشرعي والمحتوى الإسلامي الأصيل
                        </p>
                        <div class="section-features">
                            <span class="feature-tag">
                                <i class="fas fa-eye"></i>
                                رؤيتنا
                            </span>
                            <span class="feature-tag">
                                <i class="fas fa-bullseye"></i>
                                أهدافنا
                            </span>
                        </div>
                    </div>
                    <div class="section-card-footer">
                        <a href="{{ route('about') }}" class="section-btn">
                            <span>اعرف المزيد</span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="section-decoration"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Latest Sermons -->
<section class="latest-sermons-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-badge">
                <i class="fas fa-microphone"></i>
                <span>أحدث المحتوى</span>
            </div>
            <h2 class="section-title">أحدث الخطب</h2>
            <p class="section-subtitle">مجموعة مختارة من أحدث الخطب والمواعظ القيمة</p>
        </div>

        <div class="row">
            @forelse($latestSermons ?? [] as $index => $sermon)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="enhanced-sermon-card" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="card-image-wrapper">
                            @if($sermon->image)
                                <img src="{{ asset('storage/sermons/' . $sermon->image) }}"
                                     class="card-image" alt="{{ $sermon->title }}">
                            @else
                                <div class="card-image-placeholder">
                                    <div class="placeholder-icon">
                                        <i class="fas fa-microphone"></i>
                                    </div>
                                    <div class="placeholder-pattern"></div>
                                </div>
                            @endif
                            <div class="card-overlay">
                                <div class="card-category">خطبة</div>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-meta">
                                <span class="author">
                                    <i class="fas fa-user"></i>
                                    {{ $sermon->author->name ?? 'غير محدد' }}
                                </span>
                                <span class="date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $sermon->published_at ? $sermon->published_at->format('Y/m/d') : 'غير محدد' }}
                                </span>
                            </div>

                            <h5 class="card-title">{{ $sermon->title }}</h5>
                            <p class="card-excerpt">
                                {{ Str::limit($sermon->introduction ?? $sermon->content, 100) }}
                            </p>

                            <div class="card-footer">
                                <a href="{{ route('sermons.show', $sermon->id) }}" class="read-more-btn">
                                    <span>قراءة المزيد</span>
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div class="card-stats">
                                    <span class="views">
                                        <i class="fas fa-eye"></i>
                                        {{ $sermon->views_count ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-microphone-slash"></i>
                        </div>
                        <h4>لا توجد خطب متاحة حالياً</h4>
                        <p>سيتم إضافة المزيد من الخطب قريباً إن شاء الله</p>
                        <a href="{{ route('sermons.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            تصفح الخطب
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($latestSermons ?? []) > 0)
        <div class="text-center mt-5">
            <a href="{{ route('sermons.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-list me-2"></i>
                عرض جميع الخطب
                <i class="fas fa-arrow-left ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Enhanced Latest Articles -->
<section class="latest-articles-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-badge">
                <i class="fas fa-pen-fancy"></i>
                <span>مقالات مختارة</span>
            </div>
            <h2 class="section-title">أحدث مقالات المفكرين</h2>
            <p class="section-subtitle">مقالات فكرية وتربوية من نخبة من الكتاب والمفكرين</p>
        </div>

        <div class="row">
            @forelse($latestArticles ?? [] as $index => $article)
                <div class="col-lg-6 mb-4">
                    <div class="enhanced-article-card" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 150 }}">
                        <div class="article-header">
                            <div class="article-category">
                                <i class="fas fa-bookmark"></i>
                                {{ $article->category ?? 'مقال' }}
                            </div>
                            <div class="article-reading-time">
                                <i class="fas fa-clock"></i>
                                {{ $article->reading_time ?? 5 }} دقائق
                            </div>
                        </div>

                        <div class="article-content">
                            <h5 class="article-title">{{ $article->title }}</h5>

                            <div class="article-meta">
                                <span class="author">
                                    <i class="fas fa-user-edit"></i>
                                    {{ $article->author->name ?? 'غير محدد' }}
                                </span>
                                <span class="date">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $article->published_at ? $article->published_at->format('Y/m/d') : 'غير محدد' }}
                                </span>
                            </div>

                            <p class="article-excerpt">
                                {{ Str::limit($article->excerpt ?? $article->content, 150) }}
                            </p>

                            <div class="article-footer">
                                <a href="{{ route('articles.show', $article->id) }}" class="read-article-btn">
                                    <span>قراءة المقال</span>
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div class="article-stats">
                                    <span class="views">
                                        <i class="fas fa-eye"></i>
                                        {{ $article->views_count ?? 0 }}
                                    </span>
                                    @if(isset($article->tags))
                                        <span class="tags">
                                            <i class="fas fa-tags"></i>
                                            {{ count(json_decode($article->tags ?? '[]')) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="article-decoration"></div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-pen-slash"></i>
                        </div>
                        <h4>لا توجد مقالات متاحة حالياً</h4>
                        <p>سيتم إضافة المزيد من المقالات الفكرية قريباً إن شاء الله</p>
                        <a href="{{ route('thinkers.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            تصفح المقالات
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($latestArticles ?? []) > 0)
        <div class="text-center mt-5">
            <a href="{{ route('thinkers.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-list me-2"></i>
                عرض جميع المقالات
                <i class="fas fa-arrow-left ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Enhanced Upcoming Lectures -->
<section class="upcoming-lectures-section py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-badge">
                <i class="fas fa-calendar-check"></i>
                <span>فعاليات قادمة</span>
            </div>
            <h2 class="section-title">المحاضرات القادمة</h2>
            <p class="section-subtitle">لا تفوت هذه المحاضرات القيمة والفعاليات التعليمية المميزة</p>
        </div>

        <div class="row">
            @forelse($upcomingLectures ?? [] as $index => $lecture)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="enhanced-lecture-card" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                        <div class="lecture-status">
                            <span class="status-badge upcoming">
                                <i class="fas fa-clock"></i>
                                قادمة
                            </span>
                        </div>

                        <div class="lecture-content">
                            <h5 class="lecture-title">{{ $lecture->title }}</h5>

                            <div class="lecture-speaker">
                                <div class="speaker-avatar">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="speaker-info">
                                    <span class="speaker-name">{{ $lecture->speaker->name ?? 'غير محدد' }}</span>
                                    <span class="speaker-title">محاضر</span>
                                </div>
                            </div>

                            <div class="lecture-details">
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $lecture->venue ?? $lecture->location }}, {{ $lecture->city }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $lecture->scheduled_at ? $lecture->scheduled_at->format('Y/m/d') : 'غير محدد' }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $lecture->scheduled_at ? $lecture->scheduled_at->format('H:i') : 'غير محدد' }}</span>
                                </div>
                                @if($lecture->duration)
                                <div class="detail-item">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span>{{ $lecture->duration }} دقيقة</span>
                                </div>
                                @endif
                            </div>

                            <div class="lecture-footer">
                                <a href="{{ route('lectures.show', $lecture->id) }}" class="lecture-btn">
                                    <i class="fas fa-info-circle me-2"></i>
                                    تفاصيل المحاضرة
                                </a>
                                @if($lecture->contact_phone)
                                <div class="contact-info">
                                    <i class="fas fa-phone"></i>
                                    <span>{{ $lecture->contact_phone }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="lecture-decoration">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4>لا توجد محاضرات قادمة حالياً</h4>
                        <p>تابعونا للحصول على آخر المستجدات حول المحاضرات والفعاليات القادمة</p>
                        <a href="{{ route('lectures.index') }}" class="btn btn-primary">
                            <i class="fas fa-calendar me-2"></i>
                            تصفح المحاضرات
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if(count($upcomingLectures ?? []) > 0)
        <div class="text-center mt-5">
            <a href="{{ route('lectures.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-calendar me-2"></i>
                عرض جميع المحاضرات
                <i class="fas fa-arrow-left ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Enhanced Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <div class="section-badge">
                <i class="fas fa-star"></i>
                <span>مميزات فريدة</span>
            </div>
            <h2 class="section-title">مميزات المنصة</h2>
            <p class="section-subtitle">اكتشف ما يجعل منصة تمسيك الخيار الأمثل للمحتوى الإسلامي</p>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">خطب متنوعة</h5>
                        <p class="feature-description">مجموعة واسعة من الخطب في مختلف المواضيع الإسلامية المعاصرة والتراثية</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">فتاوى موثقة</h5>
                        <p class="feature-description">فتاوى شرعية من علماء معتبرين ومراجع موثوقة معتمدة من المؤسسات الدينية</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">محاضرات تفاعلية</h5>
                        <p class="feature-description">محاضرات ودروس علمية من نخبة من العلماء والدعاة مع إمكانية التفاعل المباشر</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="enhanced-feature-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">بحث متقدم</h5>
                        <p class="feature-description">إمكانية البحث السريع والمتقدم في جميع المحتويات مع فلترة ذكية للنتائج</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
        </div>

        <!-- Additional Features Row -->
        <div class="row mt-4">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="enhanced-feature-card secondary" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">متوافق مع الجوال</h5>
                        <p class="feature-description">تصميم متجاوب يعمل بسلاسة على جميع الأجهزة</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="enhanced-feature-card secondary" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">تحميل المحتوى</h5>
                        <p class="feature-description">إمكانية تحميل الخطب والمحاضرات للاستماع دون اتصال</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="enhanced-feature-card secondary" data-aos="fade-up" data-aos-delay="700">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="feature-title">محتوى آمن</h5>
                        <p class="feature-description">جميع المحتويات مراجعة ومعتمدة من قبل لجنة علمية متخصصة</p>
                    </div>
                    <div class="feature-decoration"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
