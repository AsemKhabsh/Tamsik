<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lecture->title }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lectures.css') }}">
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
        
        .lecture-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .lecture-title {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .lecture-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }
        
        .meta-item i {
            color: #1d8a4e;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .status-scheduled {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .status-completed {
            background-color: #e8f5e8;
            color: #2e7d32;
        }
        
        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }
        
        .lecture-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .lecture-description {
            font-size: 1.2rem;
            line-height: 2;
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
        
        .speaker-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .speaker-name {
            color: #1d8a4e;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .location-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .countdown {
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .countdown-title {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .countdown-timer {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('lectures.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة المحاضرات
        </a>
        
        <div class="lecture-header">
            <h1 class="lecture-title">{{ $lecture->title }}</h1>
            
            <span class="status-badge status-{{ $lecture->status }}">
                @if($lecture->status == 'scheduled')
                    <i class="fas fa-clock"></i> مجدولة
                @elseif($lecture->status == 'completed')
                    <i class="fas fa-check-circle"></i> مكتملة
                @elseif($lecture->status == 'cancelled')
                    <i class="fas fa-times-circle"></i> ملغية
                @endif
            </span>
            
            <div class="lecture-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $lecture->scheduled_at->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $lecture->scheduled_at->format('h:i A') }}</span>
                </div>
                @if($lecture->location)
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $lecture->location }}</span>
                    </div>
                @endif
                @if($lecture->duration)
                    <div class="meta-item">
                        <i class="fas fa-hourglass-half"></i>
                        <span>{{ $lecture->duration }} دقيقة</span>
                    </div>
                @endif
            </div>
            
            @if($lecture->status == 'scheduled' && $lecture->scheduled_at->isFuture())
                <div class="countdown">
                    <div class="countdown-title">الوقت المتبقي للمحاضرة</div>
                    <div class="countdown-timer" id="countdown">
                        <!-- سيتم تحديثه بـ JavaScript -->
                    </div>
                </div>
            @endif
        </div>
        
        <div class="lecture-content">
            <h3 style="color: #1d8a4e; margin-bottom: 20px;">وصف المحاضرة</h3>
            <div class="lecture-description">
                {!! nl2br(e($lecture->description)) !!}
            </div>
            
            @if($lecture->speaker)
                <div class="speaker-info">
                    <h4 style="color: #1d8a4e; margin-bottom: 10px;">المحاضر</h4>
                    <div class="speaker-name">{{ $lecture->speaker->name }}</div>
                    @if($lecture->speaker->bio)
                        <p style="margin-top: 10px; color: #666;">{{ Str::limit($lecture->speaker->bio, 200) }}</p>
                    @endif
                </div>
            @endif
            
            @if($lecture->location)
                <div class="location-info">
                    <h4 style="color: #856404; margin-bottom: 10px;">
                        <i class="fas fa-map-marker-alt"></i>
                        مكان المحاضرة
                    </h4>
                    <p style="margin: 0; color: #856404;">{{ $lecture->location }}</p>
                </div>
            @endif
        </div>
    </div>
    
    @if($lecture->status == 'scheduled' && $lecture->scheduled_at->isFuture())
        <script>
            // العد التنازلي للمحاضرة
            function updateCountdown() {
                const lectureDate = new Date('{{ $lecture->scheduled_at->toISOString() }}').getTime();
                const now = new Date().getTime();
                const distance = lectureDate - now;
                
                if (distance > 0) {
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    document.getElementById('countdown').innerHTML = 
                        days + " يوم " + hours + " ساعة " + minutes + " دقيقة " + seconds + " ثانية";
                } else {
                    document.getElementById('countdown').innerHTML = "بدأت المحاضرة!";
                }
            }
            
            // تحديث العد التنازلي كل ثانية
            setInterval(updateCountdown, 1000);
            updateCountdown(); // تشغيل فوري
        </script>
    @endif
</body>
</html>
