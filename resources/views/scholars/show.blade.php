<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $scholar->name }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scholars.css') }}">
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
        
        .scholar-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .scholar-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .scholar-name {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .scholar-title {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .scholar-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            display: block;
            font-size: 2rem;
            font-weight: bold;
            color: #1d8a4e;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .scholar-bio {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .bio-text {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }
        
        .back-link {
            color: #1d8a4e;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .sermons-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .sermon-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .sermon-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .sermon-title {
            color: #1d8a4e;
            font-size: 1.3rem;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .sermon-title:hover {
            text-decoration: underline;
        }
        
        .sermon-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .sermon-excerpt {
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('scholars.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة العلماء
        </a>
        
        <div class="scholar-header">
            <div class="scholar-avatar">
                <i class="fas fa-user-graduate"></i>
            </div>
            
            <h1 class="scholar-name">{{ $scholar->name }}</h1>
            <p class="scholar-title">عالم وداعية إسلامي</p>
            
            <div class="scholar-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['sermons_count'] }}</span>
                    <span class="stat-label">خطبة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_views']) }}</span>
                    <span class="stat-label">مشاهدة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_downloads']) }}</span>
                    <span class="stat-label">تحميل</span>
                </div>
            </div>
        </div>
        
        @if($scholar->bio)
            <div class="scholar-bio">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">نبذة عن العالم</h3>
                <div class="bio-text">
                    {!! nl2br(e($scholar->bio)) !!}
                </div>
            </div>
        @endif
        
        @if($sermons->count() > 0)
            <div class="sermons-section">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">خطب العالم</h3>
                
                @foreach($sermons as $sermon)
                    <div class="sermon-card">
                        <h4>
                            <a href="{{ route('sermons.show', $sermon->id) }}" class="sermon-title">
                                {{ $sermon->title }}
                            </a>
                        </h4>
                        <div class="sermon-meta">
                            <i class="fas fa-calendar"></i>
                            {{ $sermon->created_at->format('d/m/Y') }}
                            |
                            <i class="fas fa-eye"></i>
                            {{ number_format($sermon->views_count) }} مشاهدة
                            |
                            <i class="fas fa-download"></i>
                            {{ number_format($sermon->downloads_count) }} تحميل
                        </div>
                        <p class="sermon-excerpt">
                            {{ Str::limit(strip_tags($sermon->content), 150) }}
                        </p>
                    </div>
                @endforeach
                
                @if($sermons->hasPages())
                    <div style="margin-top: 30px; text-align: center;">
                        {{ $sermons->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="sermons-section">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">خطب العالم</h3>
                <p style="text-align: center; color: #666; padding: 40px;">
                    <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                    لا توجد خطب متاحة حالياً لهذا العالم
                </p>
            </div>
        @endif
    </div>
</body>
</html>
