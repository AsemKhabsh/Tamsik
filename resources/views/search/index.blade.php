<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>البحث - تمسك</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
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
        
        .search-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .search-title {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .search-form {
            max-width: 600px;
            margin: 0 auto;
        }
        
        .search-input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-input {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1.1rem;
            font-family: 'Amiri', serif;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #1d8a4e;
        }
        
        .search-btn {
            padding: 15px 30px;
            background-color: #1d8a4e;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            cursor: pointer;
            font-family: 'Amiri', serif;
        }
        
        .search-btn:hover {
            background-color: #155a35;
        }
        
        .search-filters {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            border: 2px solid #ddd;
            background: white;
            color: #666;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .filter-btn.active {
            border-color: #1d8a4e;
            background-color: #1d8a4e;
            color: white;
        }
        
        .filter-btn:hover {
            border-color: #1d8a4e;
            color: #1d8a4e;
        }
        
        .search-results {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .results-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .results-count {
            color: #666;
            font-size: 1.1rem;
        }
        
        .result-section {
            margin-bottom: 30px;
        }
        
        .section-title {
            color: #1d8a4e;
            font-size: 1.5rem;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .result-item {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        
        .result-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .result-title {
            color: #1d8a4e;
            font-size: 1.3rem;
            margin-bottom: 10px;
            text-decoration: none;
        }
        
        .result-title:hover {
            text-decoration: underline;
        }
        
        .result-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .result-excerpt {
            color: #555;
            line-height: 1.6;
        }
        
        .no-results {
            text-align: center;
            color: #666;
            padding: 40px;
        }
        
        .no-results i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
            color: #ddd;
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
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى الصفحة الرئيسية
        </a>
        
        <div class="search-header">
            <h1 class="search-title">البحث في موقع تمسك</h1>
            
            <form method="GET" action="{{ route('search.index') }}" class="search-form">
                <div class="search-input-group">
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="ابحث في الخطب والعلماء والمحاضرات..." 
                           class="search-input"
                           required>
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
                
                <div class="search-filters">
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}" 
                       class="filter-btn {{ $type === 'all' ? 'active' : '' }}">
                        الكل
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'sermons']) }}" 
                       class="filter-btn {{ $type === 'sermons' ? 'active' : '' }}">
                        الخطب
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'scholars']) }}" 
                       class="filter-btn {{ $type === 'scholars' ? 'active' : '' }}">
                        العلماء
                    </a>
                    <a href="{{ route('search.index', ['q' => $query, 'type' => 'lectures']) }}" 
                       class="filter-btn {{ $type === 'lectures' ? 'active' : '' }}">
                        المحاضرات
                    </a>
                </div>
            </form>
        </div>
        
        @if($query)
            <div class="search-results">
                <div class="results-header">
                    <div class="results-count">
                        @if($totalResults > 0)
                            تم العثور على {{ $totalResults }} نتيجة للبحث عن "{{ $query }}"
                        @else
                            لم يتم العثور على نتائج للبحث عن "{{ $query }}"
                        @endif
                    </div>
                </div>
                
                @if($totalResults > 0)
                    {{-- نتائج الخطب --}}
                    @if($results['sermons']->count() > 0)
                        <div class="result-section">
                            <h3 class="section-title">
                                <i class="fas fa-microphone"></i>
                                الخطب ({{ $results['sermons']->count() }})
                            </h3>
                            
                            @foreach($results['sermons'] as $sermon)
                                <div class="result-item">
                                    <h4>
                                        <a href="{{ route('sermons.show', $sermon->id) }}" class="result-title">
                                            {{ $sermon->title }}
                                        </a>
                                    </h4>
                                    <div class="result-meta">
                                        <i class="fas fa-user"></i>
                                        {{ $sermon->author->name ?? 'غير محدد' }}
                                        |
                                        <i class="fas fa-calendar"></i>
                                        {{ $sermon->created_at->format('d/m/Y') }}
                                        |
                                        <i class="fas fa-eye"></i>
                                        {{ number_format($sermon->views_count) }} مشاهدة
                                    </div>
                                    <p class="result-excerpt">
                                        {{ Str::limit(strip_tags($sermon->content), 200) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    {{-- نتائج العلماء --}}
                    @if($results['scholars']->count() > 0)
                        <div class="result-section">
                            <h3 class="section-title">
                                <i class="fas fa-user-graduate"></i>
                                العلماء ({{ $results['scholars']->count() }})
                            </h3>
                            
                            @foreach($results['scholars'] as $scholar)
                                <div class="result-item">
                                    <h4>
                                        <a href="{{ route('scholars.show', $scholar->id) }}" class="result-title">
                                            {{ $scholar->name }}
                                        </a>
                                    </h4>
                                    <div class="result-meta">
                                        <i class="fas fa-graduation-cap"></i>
                                        عالم وداعية إسلامي
                                    </div>
                                    @if($scholar->bio)
                                        <p class="result-excerpt">
                                            {{ Str::limit(strip_tags($scholar->bio), 200) }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    {{-- نتائج المحاضرات --}}
                    @if($results['lectures']->count() > 0)
                        <div class="result-section">
                            <h3 class="section-title">
                                <i class="fas fa-chalkboard-teacher"></i>
                                المحاضرات ({{ $results['lectures']->count() }})
                            </h3>
                            
                            @foreach($results['lectures'] as $lecture)
                                <div class="result-item">
                                    <h4>
                                        <a href="{{ route('lectures.show', $lecture->id) }}" class="result-title">
                                            {{ $lecture->title }}
                                        </a>
                                    </h4>
                                    <div class="result-meta">
                                        <i class="fas fa-calendar"></i>
                                        {{ $lecture->scheduled_at->format('d/m/Y h:i A') }}
                                        |
                                        <i class="fas fa-info-circle"></i>
                                        {{ $lecture->status === 'scheduled' ? 'مجدولة' : ($lecture->status === 'completed' ? 'مكتملة' : 'ملغية') }}
                                    </div>
                                    @if($lecture->description)
                                        <p class="result-excerpt">
                                            {{ Str::limit(strip_tags($lecture->description), 200) }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="no-results">
                        <i class="fas fa-search"></i>
                        <h3>لم يتم العثور على نتائج</h3>
                        <p>جرب استخدام كلمات مختلفة أو تحقق من الإملاء</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
