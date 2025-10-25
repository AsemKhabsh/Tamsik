<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة تحكم العالم') - {{ config('app.name') }}</title>
    
    <!-- Bootstrap RTL CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #1d8a4e;
            --secondary-color: #0f7346;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light-bg);
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            right: 0;
            width: 250px;
            z-index: 1000;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar .logo h4 {
            margin: 0;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-right: 3px solid transparent;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-right-color: white;
        }

        .sidebar .nav-link i {
            margin-left: 10px;
            width: 20px;
        }

        .main-content {
            margin-right: 250px;
            padding: 20px;
        }

        .top-bar {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card.primary .icon {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card.warning .icon {
            background: linear-gradient(135deg, #ffc107, #ff9800);
        }

        .stat-card.success .icon {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .stat-card.info .icon {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .question-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .question-card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .badge-status {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-right: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h4><i class="fas fa-graduation-cap"></i> لوحة العالم</h4>
                <small>{{ Auth::user()->name }}</small>
            </div>
            
            <nav class="nav flex-column mt-3">
                <a class="nav-link {{ request()->routeIs('scholar.dashboard') ? 'active' : '' }}" 
                   href="{{ route('scholar.dashboard') }}">
                    <i class="fas fa-home"></i>
                    الرئيسية
                </a>
                
                <a class="nav-link {{ request()->routeIs('scholar.questions.*') ? 'active' : '' }}" 
                   href="{{ route('scholar.questions.index') }}">
                    <i class="fas fa-question-circle"></i>
                    الأسئلة
                </a>
                
                <a class="nav-link {{ request()->routeIs('scholar.questions.index') && request('status') == 'pending' ? 'active' : '' }}" 
                   href="{{ route('scholar.questions.index', ['status' => 'pending']) }}">
                    <i class="fas fa-clock"></i>
                    قيد الانتظار
                </a>
                
                <a class="nav-link {{ request()->routeIs('scholar.questions.index') && request('status') == 'answered' ? 'active' : '' }}" 
                   href="{{ route('scholar.questions.index', ['status' => 'answered']) }}">
                    <i class="fas fa-check-circle"></i>
                    تم الرد عليها
                </a>
                
                <a class="nav-link {{ request()->routeIs('scholar.questions.index') && request('status') == 'draft' ? 'active' : '' }}" 
                   href="{{ route('scholar.questions.index', ['status' => 'draft']) }}">
                    <i class="fas fa-file-alt"></i>
                    المسودات
                </a>
                
                <hr style="border-color: rgba(255,255,255,0.2);">
                
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-globe"></i>
                    الموقع الرئيسي
                </a>
                
                <a class="nav-link" href="{{ route('profile') }}">
                    <i class="fas fa-user"></i>
                    الملف الشخصي
                </a>
                
                <a class="nav-link" href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    تسجيل الخروج
                </a>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Bar -->
            <div class="top-bar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">@yield('page-title', 'لوحة التحكم')</h5>
                    <small class="text-muted">@yield('page-description', '')</small>
                </div>
                <div>
                    <span class="text-muted">{{ now()->format('Y-m-d') }}</span>
                </div>
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
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

