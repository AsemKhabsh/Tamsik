<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $thinker->name }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/thinkers.css') }}">
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
        
        .thinker-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .thinker-avatar {
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
        
        .thinker-name {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .thinker-title {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        
        .thinker-stats {
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
        
        .thinker-bio {
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
        
        .articles-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .article-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .article-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .article-title {
            color: #1d8a4e;
            font-size: 1.3rem;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .article-title:hover {
            text-decoration: underline;
        }
        
        .article-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .article-excerpt {
            color: #555;
            line-height: 1.6;
        }
        
        .no-content {
            text-align: center;
            color: #666;
            padding: 40px;
        }
        
        .no-content i {
            font-size: 2rem;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('thinkers.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة المفكرين
        </a>
        
        <div class="thinker-header">
            <div class="thinker-avatar">
                <i class="fas fa-lightbulb"></i>
            </div>
            
            <h1 class="thinker-name">{{ $thinker->name }}</h1>
            <p class="thinker-title">مفكر وداعية إسلامي</p>
            
            <div class="thinker-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $stats['articles_count'] }}</span>
                    <span class="stat-label">مقال</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_views']) }}</span>
                    <span class="stat-label">مشاهدة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ number_format($stats['total_likes']) }}</span>
                    <span class="stat-label">إعجاب</span>
                </div>
            </div>
        </div>
        
        @if($thinker->bio)
            <div class="thinker-bio">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">نبذة عن المفكر</h3>
                <div class="bio-text">
                    {!! nl2br(e($thinker->bio)) !!}
                </div>
            </div>
        @endif
        
        <div class="articles-section">
            <h3 style="color: #1d8a4e; margin-bottom: 20px;">مقالات المفكر</h3>
            
            @if($articles->count() > 0)
                @foreach($articles as $article)
                    <div class="article-card">
                        <h4>
                            <a href="{{ route('articles.show', $article->id) }}" class="article-title">
                                {{ $article->title }}
                            </a>
                        </h4>
                        <div class="article-meta">
                            <i class="fas fa-calendar"></i>
                            {{ $article->created_at->format('d/m/Y') }}
                            |
                            <i class="fas fa-eye"></i>
                            {{ number_format($article->views_count ?? 0) }} مشاهدة
                            |
                            <i class="fas fa-heart"></i>
                            {{ number_format($article->likes_count ?? 0) }} إعجاب
                        </div>
                        <p class="article-excerpt">
                            {{ Str::limit(strip_tags($article->content), 150) }}
                        </p>
                    </div>
                @endforeach
            @else
                <div class="no-content">
                    <i class="fas fa-info-circle"></i>
                    لا توجد مقالات متاحة حالياً لهذا المفكر
                </div>
            @endif
        </div>
    </div>
</body>
</html>
