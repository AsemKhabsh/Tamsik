<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المفكرون والدعاة - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thinkers.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2c5f2d 0%, #97bc62 100%);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e4620 0%, #7a9a4d 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 95, 45, 0.3);
        }

        .btn-outline-primary {
            background: transparent;
            color: #2c5f2d;
            border: 2px solid #2c5f2d;
        }

        .btn-outline-primary:hover {
            background: #2c5f2d;
            color: white;
        }

        .btn-lg {
            padding: 12px 24px;
            font-size: 16px;
        }

        .d-flex {
            display: flex;
        }

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .me-2 {
            margin-left: 0.5rem;
        }

        .ms-2 {
            margin-right: 0.5rem;
        }
    </style>
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
                    <li><a href="{{ route('sermons.index') }}"><i class="fas fa-book-open"></i> الخطب الجاهزة</a></li>
                    <li><a href="#"><i class="fas fa-pen"></i> إعداد خطبة</a></li>
                    <li><a href="{{ route('scholars.index') }}"><i class="fas fa-user-graduate"></i> العلماء</a></li>
                    <li><a href="{{ route('thinkers.index') }}" class="active"><i class="fas fa-lightbulb"></i> المفكرون والدعاة</a></li>
                    <li><a href="{{ route('lectures.index') }}"><i class="fas fa-microphone"></i> المحاضرات والدروس</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container page-content">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-lightbulb"></i>
                        المفكرون والدعاة
                    </h1>
                    <p class="page-description">
                        اقرأ مقالات المفكرين والدعاة المعاصرين وآرائهم في القضايا المختلفة
                    </p>
                </div>
                @auth
                    @if(in_array(auth()->user()->user_type, ['admin', 'scholar', 'thinker', 'data_entry']))
                        <div>
                            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                إضافة مقال جديد
                            </a>
                            <a href="{{ route('articles.my') }}" class="btn btn-outline-primary btn-lg ms-2">
                                <i class="fas fa-newspaper me-2"></i>
                                مقالاتي
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="thinkers-grid">
            @forelse($thinkers as $thinker)
                <div class="thinker-card">
                    <div class="thinker-avatar">
                        @if($thinker->avatar)
                            <img src="{{ asset('storage/' . $thinker->avatar) }}" alt="{{ $thinker->name }}">
                        @else
                            <i class="fas fa-lightbulb"></i>
                        @endif
                    </div>
                    <div class="thinker-info">
                        <h3 class="thinker-name">{{ $thinker->name }}</h3>
                        <p class="thinker-title">{{ $thinker->title ?? 'مفكر ومؤلف' }}</p>
                        <p class="thinker-bio">{{ Str::limit($thinker->bio, 100) }}</p>
                        
                        <div class="thinker-stats">
                            <div class="stat-item">
                                <i class="fas fa-newspaper"></i>
                                <span>0 مقال</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>0 مشاهدة</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('thinkers.show', $thinker->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
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
</body>
</html>
