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
            color: #666 !important;
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
            color: #1d8a4e !important;
        }

        .stat-label {
            color: #666 !important;
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
            color: #333 !important;
        }

        h3 {
            color: #1d8a4e !important;
            font-weight: bold !important;
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
            color: #1d8a4e !important;
            font-size: 1.3rem;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .sermon-title:hover {
            color: #0f7346 !important;
            text-decoration: underline;
        }

        .sermon-meta {
            color: #666 !important;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .sermon-excerpt {
            color: #555 !important;
            line-height: 1.6;
        }

        .sermon-excerpt strong {
            color: #333 !important;
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
            
            <h1 class="scholar-name" style="color: #1d8a4e !important;">{{ $scholar->name }}</h1>
            <p class="scholar-title" style="color: #666 !important;">عالم وداعية إسلامي</p>

            <div class="scholar-stats">
                <div class="stat-item">
                    <span class="stat-number" style="color: #1d8a4e !important;">{{ $stats['sermons_count'] }}</span>
                    <span class="stat-label" style="color: #666 !important;">خطبة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" style="color: #1d8a4e !important;">{{ number_format($stats['total_views']) }}</span>
                    <span class="stat-label" style="color: #666 !important;">مشاهدة</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" style="color: #1d8a4e !important;">{{ number_format($stats['total_downloads']) }}</span>
                    <span class="stat-label" style="color: #666 !important;">تحميل</span>
                </div>
            </div>
        </div>
        
        @if($scholar->bio)
            <div class="scholar-bio">
                <h3 style="color: #1d8a4e !important; margin-bottom: 20px; font-weight: bold;">
                    <i class="fas fa-info-circle me-2"></i>
                    نبذة عن العالم
                </h3>
                <div class="bio-text" style="color: #333 !important;">
                    {!! nl2br(e($scholar->bio)) !!}
                </div>
            </div>
        @endif

        @if($sermons->count() > 0)
            <div class="sermons-section">
                <h3 style="color: #1d8a4e !important; margin-bottom: 20px; font-weight: bold;">
                    <i class="fas fa-microphone me-2"></i>
                    خطب العالم
                </h3>
                
                @foreach($sermons as $sermon)
                    <div class="sermon-card">
                        <h4>
                            <a href="{{ route('sermons.show', $sermon->id) }}" class="sermon-title" style="color: #1d8a4e !important;">
                                {{ $sermon->title }}
                            </a>
                        </h4>
                        <div class="sermon-meta" style="color: #666 !important;">
                            <i class="fas fa-calendar"></i>
                            {{ $sermon->created_at->format('d/m/Y') }}
                            |
                            <i class="fas fa-eye"></i>
                            {{ number_format($sermon->views_count) }} مشاهدة
                            |
                            <i class="fas fa-download"></i>
                            {{ number_format($sermon->downloads_count) }} تحميل
                        </div>
                        <p class="sermon-excerpt" style="color: #555 !important;">
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
                <h3 style="color: #1d8a4e !important; margin-bottom: 20px; font-weight: bold;">
                    <i class="fas fa-microphone me-2"></i>
                    خطب العالم
                </h3>
                <p style="text-align: center; color: #666 !important; padding: 40px;">
                    <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #999 !important;"></i>
                    لا توجد خطب متاحة حالياً لهذا العالم
                </p>
            </div>
        @endif

        {{-- Fatwas Section --}}
        <div class="sermons-section" style="margin-top: 40px;">
            <h3 style="color: #1d8a4e !important; margin-bottom: 20px; font-weight: bold;">
                <i class="fas fa-balance-scale me-2"></i>
                فتاوى العالم
            </h3>

            @if(isset($fatwas) && $fatwas->count() > 0)
                @foreach($fatwas as $fatwa)
                    <div class="sermon-card">
                        <h4>
                            <a href="{{ route('fatwas.show', $fatwa->id) }}" class="sermon-title" style="color: #1d8a4e !important;">
                                {{ $fatwa->title }}
                            </a>
                        </h4>
                        <div class="sermon-meta" style="color: #666 !important;">
                            <i class="fas fa-tag"></i>
                            {{ $fatwa->category }}
                            |
                            <i class="fas fa-calendar"></i>
                            {{ $fatwa->published_at ? $fatwa->published_at->format('d/m/Y') : $fatwa->created_at->format('d/m/Y') }}
                            |
                            <i class="fas fa-eye"></i>
                            {{ number_format($fatwa->views_count ?? 0) }} مشاهدة
                        </div>
                        <p class="sermon-excerpt" style="color: #555 !important;">
                            <strong style="color: #333 !important;">السؤال:</strong> {{ Str::limit(strip_tags($fatwa->question), 150) }}
                        </p>
                    </div>
                @endforeach

                @if($fatwas->hasPages())
                    <div style="margin-top: 30px; text-align: center;">
                        {{ $fatwas->links() }}
                    </div>
                @endif
            @else
                <p style="text-align: center; color: #666 !important; padding: 40px;">
                    <i class="fas fa-info-circle" style="font-size: 2rem; margin-bottom: 10px; display: block; color: #999 !important;"></i>
                    لا توجد فتاوى متاحة حالياً لهذا العالم
                </p>
            @endif

            <div style="text-align: center; margin-top: 30px; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                {{-- زر فتاوى العالم فقط --}}
                <a href="{{ route('fatwas.scholar', $scholar->id) }}" class="btn btn-primary" style="padding: 12px 30px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: bold;">
                    <i class="fas fa-user-graduate me-2"></i>
                    فتاوى {{ $scholar->name }}
                </a>

                {{-- زر جميع الفتاوى --}}
                <a href="{{ route('fatwas.index') }}" class="btn" style="background: #1d8a4e !important; color: white !important; padding: 12px 30px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: bold; box-shadow: 0 2px 8px rgba(29,138,78,0.3);">
                    <i class="fas fa-book me-2"></i>
                    تصفح جميع الفتاوى
                </a>

                {{-- زر طرح سؤال --}}
                @auth
                    <a href="{{ route('questions.ask') }}" class="btn btn-success" style="padding: 12px 30px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: bold;">
                        <i class="fas fa-question-circle me-2"></i>
                        اطرح سؤالاً
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn" style="background: #6c757d !important; color: white !important; padding: 12px 30px; border-radius: 5px; text-decoration: none; display: inline-block; font-weight: bold; box-shadow: 0 2px 8px rgba(108,117,125,0.3);">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        سجل دخولك لطرح سؤال
                    </a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
