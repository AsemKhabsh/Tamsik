<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'تمسيك - منصة إسلامية شاملة')</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Unified Theme CSS (الهوية البصرية الموحدة) -->
    <link rel="stylesheet" href="{{ asset('css/unified-theme.css') }}?v=1.0.0">

    <!-- Toast Notifications CSS -->
    <link rel="stylesheet" href="{{ asset('css/toast-notifications.css') }}?v=1.0.0">

    <!-- Performance Optimizations CSS -->
    <link rel="stylesheet" href="{{ asset('css/performance-optimizations.css') }}?v=1.0.0">

    <!-- Dark Mode Toggle CSS -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode-toggle.css') }}?v=1.0.0">

    <!-- Additional CSS Files (only if needed for specific pages) -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ asset('css/scholars.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ asset('css/thinkers.css') }}?v=1.0.0">
    <link rel="stylesheet" href="{{ asset('css/lectures.css') }}?v=1.0.0">
    
    <style>
        /* تطبيق الخطوط من التصميم الموحد */
        body {
            font-family: var(--font-secondary, 'Cairo', 'Noto Sans Arabic', sans-serif) !important;
            background-color: var(--bg-light, #f8f9fa) !important;
        }

        h1, h2, h3, h4, h5, h6, .page-title {
            font-family: var(--font-primary, 'Amiri', serif) !important;
        }

        /* تطبيق ألوان التصميم الموحد على Navbar */
        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: #d4af37 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-dark, #0f7346) 0%, var(--primary-color, #1d8a4e) 100%) !important;
            box-shadow: var(--shadow-md, 0 4px 15px rgba(0, 0, 0, 0.1)) !important;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 10px;
            transition: var(--transition-normal, all 0.3s ease) !important;
        }

        .navbar-nav .nav-link:hover {
            color: var(--secondary-color, #d4af37) !important;
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-dark, #0f7346) 0%, var(--primary-color, #1d8a4e) 100%) !important;
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #2c5530 0%, #3d7c47 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 85, 48, 0.4);
        }
        
        .footer {
            background: var(--bg-dark, #1a3a4a);
            color: white;
            padding: 40px 0 20px;
            margin-top: 50px;
        }
        
        .section-title {
            color: #2c5530;
            font-weight: 700;
            margin-bottom: 30px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #2c5530 0%, #3d7c47 100%);
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c5530;
        }
        
        .search-box {
            background: white;
            border-radius: 50px;
            padding: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .search-box input {
            border: none;
            outline: none;
            padding: 15px 25px;
            border-radius: 50px;
        }
        
        .search-box button {
            border-radius: 50px;
            padding: 15px 25px;
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 50px 0;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }
        }

        /* Footer Styling */
        .footer {
            background: linear-gradient(135deg, #0f7346 0%, #1d8a4e 100%) !important;
            color: #f8f9fa !important;
            padding: 60px 0 20px 0;
        }

        .footer h5 {
            color: #d4af37 !important;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer .text-light {
            color: #f8f9fa !important;
        }

        .footer .text-light:hover {
            color: #d4af37 !important;
        }

        .footer hr {
            border-color: rgba(255, 255, 255, 0.2) !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-mosque me-2"></i>
                تمسيك
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="تبديل القائمة">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">
                            <i class="fas fa-home me-1"></i>
                            الرئيسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sermons.index') }}">
                            <i class="fas fa-microphone me-1"></i>
                            الخطب
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'preacher', 'scholar', 'thinker', 'data_entry']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="contentDropdown" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-edit me-1"></i>
                                    إنشاء محتوى
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="contentDropdown">
                                    {{-- ملاحظة: العالم (scholar) لديه جميع صلاحيات الخطيب (preacher) --}}
                                    @if(auth()->user()->hasAnyRole(['admin', 'scholar', 'preacher']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('sermons.prepare') }}">
                                                <i class="fas fa-pen me-2"></i>
                                                إعداد خطبة جديدة
                                            </a>
                                        </li>
                                    @endif

                                    @if(auth()->user()->hasAnyRole(['admin', 'scholar', 'preacher']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('lectures.create') }}">
                                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                                إضافة محاضرة جديدة
                                            </a>
                                        </li>
                                    @endif

                                    <li><hr class="dropdown-divider"></li>

                                    {{-- ملاحظة: العالم (scholar) لديه جميع صلاحيات الخطيب (preacher) --}}
                                    @if(auth()->user()->hasAnyRole(['admin', 'scholar', 'preacher']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('sermon.my') }}">
                                                <i class="fas fa-book-open me-2"></i>
                                                خطبي
                                            </a>
                                        </li>
                                    @endif

                                    @if(auth()->user()->hasAnyRole(['admin', 'scholar', 'preacher']))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('lectures.my') }}">
                                                <i class="fas fa-chalkboard-teacher me-2"></i>
                                                محاضراتي
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('scholars.index') }}">
                            <i class="fas fa-user-graduate me-1"></i>
                            العلماء
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('thinkers.index') }}">
                            <i class="fas fa-lightbulb me-1"></i>
                            المفكرون
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('lectures.index') }}">
                            <i class="fas fa-chalkboard-teacher me-1"></i>
                            المحاضرات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('search.index') }}">
                            <i class="fas fa-search me-1"></i>
                            البحث
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                دخول
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                تسجيل
                            </a>
                        </li>
                    @else
                        <!-- Notifications Bell -->
                        <li class="nav-item dropdown me-2">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-lg"></i>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ Auth::user()->unreadNotifications->count() > 9 ? '9+' : Auth::user()->unreadNotifications->count() }}
                                        <span class="visually-hidden">إشعارات غير مقروءة</span>
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" style="min-width: 350px; max-height: 400px; overflow-y: auto;">
                                <li class="dropdown-header d-flex justify-content-between align-items-center">
                                    <span><strong>الإشعارات</strong></span>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.read-all') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-link text-success p-0" style="text-decoration: none;">
                                                تحديد الكل كمقروء
                                            </button>
                                        </form>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <li>
                                        <a class="dropdown-item {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ route('notifications.read', $notification->id) }}" onclick="event.preventDefault(); document.getElementById('notification-form-{{ $notification->id }}').submit();">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-comment-dots text-success me-2 mt-1"></i>
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 small">{{ Str::limit($notification->data['message'] ?? 'إشعار جديد', 60) }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                        <form id="notification-form-{{ $notification->id }}" action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                @empty
                                    <li>
                                        <div class="dropdown-item text-center text-muted py-3">
                                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                                            <p class="mb-0">لا توجد إشعارات جديدة</p>
                                        </div>
                                    </li>
                                @endforelse

                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-center text-success" href="{{ route('notifications.index') }}">
                                            <strong>عرض جميع الإشعارات</strong>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">الملف الشخصي</a></li>
                                <li><a class="dropdown-item" href="{{ route('favorites') }}">المفضلات</a></li>
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                    الإشعارات
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="badge bg-danger ms-1">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a></li>
                                @if(Auth::user()->hasRole('scholar'))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('scholar.dashboard') }}">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        لوحة العالم
                                    </a></li>
                                @endif
                                @can('view_admin_panel')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">لوحة الإدارة</a></li>
                                @endcan
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            خروج
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-mosque me-2"></i>تمسيك</h5>
                    <p>منصة إسلامية شاملة تهدف إلى نشر العلم الشرعي والثقافة الإسلامية الأصيلة</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>روابط سريعة</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('sermons.index') }}" class="text-light text-decoration-none">الخطب</a></li>
                        <li><a href="{{ route('lectures.index') }}" class="text-light text-decoration-none">المحاضرات</a></li>
                        <li><a href="{{ route('articles.index') }}" class="text-light text-decoration-none">المقالات</a></li>
                        <li><a href="{{ route('scholars.index') }}" class="text-light text-decoration-none">العلماء</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>تواصل معنا</h5>
                    <p><i class="fas fa-envelope me-2"></i>info@tamsik.org</p>
                    <p><i class="fas fa-phone me-2"></i>+967770617151</p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-telegram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} تمسيك. جميع الحقوق محفوظة.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>تم التطوير بواسطة: م/ عاصم خبش | <i class="fas fa-phone"></i> +967780002776</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Original JS Files -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Enhanced Home Page Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100
        });

        // Animated Counter for Statistics
        function animateCounter(element, target) {
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                element.textContent = Math.floor(current);
            }, 30);
        }

        // Initialize counters when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.stats-number[data-count]');

            // Intersection Observer for counters
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.getAttribute('data-count'));
                        animateCounter(entry.target, target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });

            counters.forEach(counter => {
                observer.observe(counter);
            });

            // Enhanced search input focus effects
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                searchInput.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            }

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Add loading states for buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.href && !this.href.includes('#')) {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحميل...';
                }
            });
        });

        // Enhanced section cards hover effects
        document.querySelectorAll('.section-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Parallax effect for hero background
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroBackground = document.querySelector('.hero-background');
            if (heroBackground) {
                heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Lazy loading for images
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>

    <!-- Toast Notifications JS -->
    <script src="{{ asset('js/toast-notifications.js') }}?v=1.0.0"></script>

    @stack('scripts')

    {{-- Laravel Session Messages as Toasts --}}
    @if(session('success'))
        <div data-toast-success="{{ session('success') }}" style="display: none;"></div>
    @endif

    @if(session('error'))
        <div data-toast-error="{{ session('error') }}" style="display: none;"></div>
    @endif

    @if(session('warning'))
        <div data-toast-warning="{{ session('warning') }}" style="display: none;"></div>
    @endif

    @if(session('info'))
        <div data-toast-info="{{ session('info') }}" style="display: none;"></div>
    @endif

    {{-- Include Unified Modals --}}
    @include('components.modals')

    {{-- Performance Optimizations JS --}}
    <script src="{{ asset('js/performance-optimizations.js') }}?v=1.0.0" defer></script>

    {{-- PWA Installer JS --}}
    <script src="{{ asset('js/pwa-installer.js') }}?v=1.0.0" defer></script>

    {{-- Dark Mode Toggle JS --}}
    <script src="{{ asset('js/dark-mode.js') }}?v=1.0.0"></script>
</body>
</html>
