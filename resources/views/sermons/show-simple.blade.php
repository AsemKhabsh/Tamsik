<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sermon->title }} - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sermons.css') }}">
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
        
        .sermon-header {
            background: white !important;
            padding: 30px !important;
            border-radius: 10px !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
            margin-bottom: 30px !important;
            display: block !important;
        }

        .sermon-title {
            color: #1d8a4e !important;
            font-size: 2.5rem !important;
            margin-bottom: 25px !important;
            padding-bottom: 20px !important;
            font-weight: bold !important;
            text-align: center !important;
            border-bottom: 2px solid #f0f0f0 !important;
        }

        .sermon-meta {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 30px !important;
            margin-bottom: 20px !important;
            flex-wrap: wrap !important;
        }
        
        .meta-item {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            color: #666 !important;
            white-space: nowrap !important;
            font-weight: 500 !important;
        }

        .meta-item i {
            color: #1d8a4e !important;
            font-size: 1rem !important;
        }
        
        .sermon-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .sermon-text {
            font-size: 1.2rem;
            line-height: 2;
            text-align: justify;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #1d8a4e;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #155a35;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
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
        
        .tags {
            margin-top: 20px;
        }
        
        .tag {
            display: inline-block;
            background-color: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.9rem;
            margin: 5px 5px 5px 0;
        }
        
        .references {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .reference-item {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        /* تنسيقات متجاوبة */
        @media (max-width: 768px) {
            .sermon-title {
                font-size: 1.8rem !important;
            }

            .sermon-meta {
                gap: 15px !important;
            }

            .meta-item {
                font-size: 0.9rem !important;
            }
        }

        @media (max-width: 480px) {
            .sermon-title {
                font-size: 1.5rem !important;
            }

            .sermon-meta {
                gap: 10px !important;
            }

            .meta-item {
                font-size: 0.85rem !important;
            }
        }
        
        .reference-item i {
            color: #1d8a4e;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('sermons.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة الخطب
        </a>
        
        <div class="sermon-header">
            <h1 class="sermon-title">{{ $sermon->title }}</h1>
            
            <div class="sermon-meta">
                <div class="meta-item">
                    <i class="fas fa-tag"></i>
                    <span>{{ $categories[$sermon->category] ?? $sermon->category }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $sermon->author->name ?? 'غير محدد' }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ ($sermon->published_at ?? $sermon->created_at)->format('d/m/Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($sermon->views_count) }} مشاهدة</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-download"></i>
                    <span>{{ number_format($sermon->downloads_count) }} تحميل</span>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('sermons.download', $sermon->id) }}" class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    تحميل الخطبة
                </a>
                <button class="btn btn-secondary" onclick="shareSermon()">
                    <i class="fas fa-share"></i>
                    مشاركة
                </button>
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    طباعة
                </button>
            </div>
        </div>
        
        <div class="sermon-content">
            <h3 style="color: #1d8a4e; margin-bottom: 20px;">محتوى الخطبة</h3>
            <div class="sermon-text">
                {!! $sermon->content !!}
            </div>
            
            @if($sermon->tags && count($sermon->tags ?? []) > 0)
                <div class="tags">
                    <h4 style="color: #1d8a4e;">الكلمات المفتاحية:</h4>
                    @foreach($sermon->tags ?? [] as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif
            
            @if($sermon->references && count($sermon->references ?? []) > 0)
                <div class="references">
                    <h4 style="color: #1d8a4e; margin-bottom: 15px;">المراجع والمصادر:</h4>
                    @foreach($sermon->references ?? [] as $reference)
                        <div class="reference-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $reference }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        @if($relatedSermons->count() > 0)
            <div class="sermon-content">
                <h3 style="color: #1d8a4e; margin-bottom: 20px;">خطب ذات صلة</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($relatedSermons as $related)
                        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
                            <h4>
                                <a href="{{ route('sermons.show', $related->id) }}" style="color: #1d8a4e; text-decoration: none;">
                                    {{ $related->title }}
                                </a>
                            </h4>
                            <p style="color: #666; margin: 10px 0;">
                                {{ Str::limit(strip_tags($related->content), 100) }}
                            </p>
                            <small style="color: #999;">
                                بواسطة: {{ $related->author->name ?? 'غير محدد' }} | 
                                {{ $related->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <script>
        function shareSermon() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $sermon->title }}',
                    text: 'اطلع على هذه الخطبة من موقع تمسيك',
                    url: window.location.href
                });
            } else {
                navigator.clipboard.writeText(window.location.href).then(function() {
                    alert('تم نسخ رابط الخطبة');
                }, function() {
                    alert('فشل في نسخ الرابط');
                });
            }
        }
    </script>
</body>
</html>
