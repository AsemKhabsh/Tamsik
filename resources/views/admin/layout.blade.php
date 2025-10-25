<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - تمسيك</title>
    
    <!-- Bootstrap RTL -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- الخطوط العربية -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Noto Sans Arabic', 'Amiri', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(-5px);
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-left: 10px;
        }
        
        .main-content {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin: 20px;
            padding: 30px;
        }
        
        .page-header {
            background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-right: 4px solid #1d8a4e;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card .icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #2c5530 0%, #1d8a4e 100%);
            transform: translateY(-2px);
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .table thead {
            background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);
            color: white;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #1d8a4e;
            box-shadow: 0 0 0 0.2rem rgba(29, 138, 78, 0.25);
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            padding: 20px;
            display: block;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .user-info {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 15px;
            margin: 20px 10px;
            color: white;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                right: -250px;
                width: 250px;
                z-index: 1000;
                transition: right 0.3s ease;
            }
            
            .sidebar.show {
                right: 0;
            }
            
            .main-content {
                margin: 10px;
                padding: 20px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <a href="{{ route('admin.dashboard') }}" class="logo">
                        <i class="fas fa-tachometer-alt"></i>
                        لوحة التحكم
                    </a>
                    
                    <div class="user-info">
                        <div class="mb-2">
                            <i class="fas fa-user-circle fa-2x"></i>
                        </div>
                        <div>{{ Auth::user()->name }}</div>
                        <small>{{ Auth::user()->role }}</small>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-home"></i>
                            الرئيسية
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                            <i class="fas fa-users"></i>
                            المستخدمين
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.sermons*') ? 'active' : '' }}" href="{{ route('admin.sermons') }}">
                            <i class="fas fa-book-open"></i>
                            الخطب
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.lectures*') ? 'active' : '' }}" href="{{ route('admin.lectures') }}">
                            <i class="fas fa-microphone"></i>
                            المحاضرات
                        </a>
                        
                        <a class="nav-link {{ request()->routeIs('admin.scholars*') ? 'active' : '' }}" href="{{ route('admin.scholars') }}">
                            <i class="fas fa-user-graduate"></i>
                            العلماء
                        </a>

                        <a class="nav-link {{ request()->routeIs('admin.articles*') ? 'active' : '' }}" href="{{ route('admin.articles.pending') }}">
                            <i class="fas fa-newspaper"></i>
                            المقالات المعلقة
                            @if(isset($stats['pending_articles']) && $stats['pending_articles'] > 0)
                                <span class="badge bg-warning text-dark ms-2">{{ $stats['pending_articles'] }}</span>
                            @endif
                        </a>

                        <a class="nav-link {{ request()->routeIs('admin.fatwas*') ? 'active' : '' }}" href="{{ route('admin.fatwas') }}">
                            <i class="fas fa-question-circle"></i>
                            الفتاوى
                            @if(isset($stats['pending_fatwas']) && $stats['pending_fatwas'] > 0)
                                <span class="badge bg-info text-dark ms-2">{{ $stats['pending_fatwas'] }}</span>
                            @endif
                        </a>

                        <div class="nav-link">
                            <hr style="border-color: rgba(255,255,255,0.2);">
                        </div>
                        
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="fas fa-external-link-alt"></i>
                            عرض الموقع
                        </a>
                        
                        <a class="nav-link" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            تسجيل الخروج
                        </a>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content">
                    <!-- Mobile Menu Button -->
                    <button class="btn btn-primary d-md-none mb-3" type="button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                        القائمة
                    </button>
                    
                    <!-- Page Header -->
                    <div class="page-header">
                        <h1 class="mb-0">@yield('page-title', 'لوحة التحكم')</h1>
                        <p class="mb-0 mt-2">@yield('page-description', 'إدارة محتوى موقع تمسيك')</p>
                    </div>
                    
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>يرجى تصحيح الأخطاء التالية:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }
        
        // إخفاء الـ sidebar عند النقر خارجها في الهاتف
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // تأكيد الحذف
        function confirmDelete(message = 'هل أنت متأكد من الحذف؟') {
            return confirm(message);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
