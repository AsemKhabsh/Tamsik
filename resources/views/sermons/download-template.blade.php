<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sermon->title }} - تمسيك</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
            line-height: 1.8;
            margin: 40px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2c5530;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #2c5530;
            margin-bottom: 10px;
        }
        .meta {
            color: #666;
            font-size: 14px;
        }
        .content {
            margin: 30px 0;
        }
        .section {
            margin: 25px 0;
        }
        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c5530;
            margin-bottom: 15px;
            border-right: 4px solid #2c5530;
            padding-right: 15px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 20px;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body { margin: 20px; }
            .header { page-break-after: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $sermon->title }}</div>
        <div class="meta">
            @if($sermon->author)
                الكاتب: {{ $sermon->author->name }} | 
            @endif
            التصنيف: {{ $sermon->category }} | 
            تاريخ النشر: {{ $sermon->published_at ? $sermon->published_at->format('Y/m/d') : 'غير محدد' }}
        </div>
    </div>

    <div class="content">
        @if($sermon->introduction)
        <div class="section">
            <div class="section-title">المقدمة</div>
            <div>{!! nl2br(e($sermon->introduction)) !!}</div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">محتوى الخطبة</div>
            <div>{!! nl2br(e($sermon->content)) !!}</div>
        </div>

        @if($sermon->conclusion)
        <div class="section">
            <div class="section-title">الخاتمة</div>
            <div>{!! nl2br(e($sermon->conclusion)) !!}</div>
        </div>
        @endif

        @if($sermon->tags && count($sermon->tags) > 0)
        <div class="section">
            <div class="section-title">الكلمات المفتاحية</div>
            <div>{{ implode(' - ', $sermon->tags) }}</div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>تم تحميل هذه الخطبة من منصة تمسيك الإسلامية</p>
        <p>{{ config('app.url') }} | {{ now()->format('Y/m/d H:i') }}</p>
    </div>
</body>
</html>
