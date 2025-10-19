<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محاضراتي - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Amiri', serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .page-title {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Amiri', serif;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        .btn-edit {
            background: #ffc107;
            color: #333;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .lectures-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        
        .lecture-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .lecture-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .lecture-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
        }
        
        .lecture-content {
            padding: 20px;
        }
        
        .lecture-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #1d8a4e;
            margin-bottom: 10px;
        }
        
        .lecture-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .lecture-meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .lecture-description {
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        
        .lecture-actions {
            display: flex;
            gap: 10px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .badge-free {
            background: #28a745;
            color: white;
        }
        
        .badge-paid {
            background: #ffc107;
            color: #333;
        }
        
        .empty-state {
            background: white;
            padding: 60px 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: #666;
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #999;
            margin-bottom: 20px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 768px) {
            .lectures-grid {
                grid-template-columns: 1fr;
            }
            
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">محاضراتي</h1>
            <p class="page-subtitle">إدارة المحاضرات التي أضفتها</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        <div class="action-bar">
            <a href="{{ route('lectures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                العودة إلى قائمة المحاضرات
            </a>
            <a href="{{ route('lectures.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                إضافة محاضرة جديدة
            </a>
        </div>
        
        @if($lectures->count() > 0)
            <div class="lectures-grid">
                @foreach($lectures as $lecture)
                    <div class="lecture-card">
                        @if($lecture->featured_image)
                            <img src="{{ asset('storage/' . $lecture->featured_image) }}" alt="{{ $lecture->title }}" class="lecture-image">
                        @else
                            <div class="lecture-image"></div>
                        @endif
                        
                        <div class="lecture-content">
                            <h3 class="lecture-title">{{ $lecture->title }}</h3>
                            
                            <div class="lecture-meta">
                                <div class="lecture-meta-item">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ $lecture->category }}</span>
                                </div>
                                <div class="lecture-meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ \Carbon\Carbon::parse($lecture->scheduled_at)->format('Y/m/d') }}</span>
                                </div>
                                <div class="lecture-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $lecture->duration }} دقيقة</span>
                                </div>
                            </div>
                            
                            <p class="lecture-description">{{ Str::limit($lecture->description, 100) }}</p>
                            
                            <div style="margin-bottom: 10px;">
                                @if($lecture->is_free)
                                    <span class="badge badge-free">مجانية</span>
                                @else
                                    <span class="badge badge-paid">{{ $lecture->price }} ريال</span>
                                @endif
                            </div>
                            
                            <div class="lecture-actions">
                                <a href="{{ route('lectures.show', $lecture->id) }}" class="btn btn-secondary btn-sm" style="flex: 1;">
                                    <i class="fas fa-eye"></i>
                                    عرض
                                </a>
                                <a href="{{ route('lectures.edit', $lecture->id) }}" class="btn btn-edit btn-sm" style="flex: 1;">
                                    <i class="fas fa-edit"></i>
                                    تعديل
                                </a>
                                <form action="{{ route('lectures.destroy', $lecture->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('هل أنت متأكد من حذف هذه المحاضرة؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-sm" style="width: 100%;">
                                        <i class="fas fa-trash"></i>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{ $lectures->links() }}
        @else
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>لا توجد محاضرات بعد</h3>
                <p>لم تقم بإضافة أي محاضرات حتى الآن</p>
                <a href="{{ route('lectures.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة محاضرة جديدة
                </a>
            </div>
        @endif
    </div>
</body>
</html>

