@extends('layouts.app')

@section('title', 'الخطب الجاهزة - تمسك')

@push('styles')
<style>
    .sermons-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    .sermon-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .sermon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .sermon-header {
        background: linear-gradient(135deg, #2c5530, #3d7c47);
        color: white;
        padding: 20px;
        position: relative;
    }

    .sermon-category {
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 10px;
    }

    .sermon-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .sermon-title a {
        color: white;
        text-decoration: none;
    }

    .sermon-title a:hover {
        text-decoration: underline;
    }

    .sermon-author {
        font-size: 0.9rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sermon-body {
        padding: 20px;
    }

    .sermon-excerpt {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .sermon-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid #e9ecef;
        font-size: 0.9rem;
        color: #666;
    }

    .sermon-stats {
        display: flex;
        gap: 15px;
    }

    .sermon-date {
        color: #999;
    }

    .search-section {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .search-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input {
        flex: 1;
        min-width: 250px;
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
    }

    .search-input:focus {
        outline: none;
        border-color: #2c5530;
    }

    .category-filter {
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        background: white;
        min-width: 150px;
    }

    .search-btn {
        padding: 12px 25px;
        background: linear-gradient(135deg, #2c5530, #3d7c47);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
    }

    .search-btn:hover {
        background: linear-gradient(135deg, #1e3a21, #2d5a34);
    }

    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 4px solid #2c5530;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #2c5530;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }

    .no-sermons {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .no-sermons i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .sermons-grid {
            grid-template-columns: 1fr;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input, .category-filter, .search-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fas fa-microphone me-3"></i>
        الخطب الجاهزة
    </h1>
    <p class="page-subtitle">مجموعة شاملة من الخطب الإسلامية المعدة بعناية</p>
</div>

{{-- إحصائيات سريعة --}}
<div class="stats-section">
    <div class="stat-card">
        <div class="stat-number">{{ $sermons->total() }}</div>
        <div class="stat-label">إجمالي الخطب</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $sermons->where('category', 'عقيدة')->count() }}</div>
        <div class="stat-label">خطب العقيدة</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $sermons->where('category', 'عبادات')->count() }}</div>
        <div class="stat-label">خطب العبادات</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $sermons->where('category', 'أخلاق')->count() }}</div>
        <div class="stat-label">خطب الأخلاق</div>
    </div>
</div>

{{-- قسم البحث والفلترة --}}
<div class="search-section">
    <form method="GET" action="{{ route('sermons.index') }}" class="search-form">
        <input type="text" 
               name="search" 
               value="{{ request('search') }}" 
               placeholder="ابحث في الخطب..." 
               class="search-input">
        
        <select name="category" class="category-filter">
            <option value="">جميع التصنيفات</option>
            <option value="عقيدة" {{ request('category') == 'عقيدة' ? 'selected' : '' }}>العقيدة</option>
            <option value="عبادات" {{ request('category') == 'عبادات' ? 'selected' : '' }}>العبادات</option>
            <option value="معاملات" {{ request('category') == 'معاملات' ? 'selected' : '' }}>المعاملات</option>
            <option value="أخلاق" {{ request('category') == 'أخلاق' ? 'selected' : '' }}>الأخلاق</option>
            <option value="سيرة" {{ request('category') == 'سيرة' ? 'selected' : '' }}>السيرة النبوية</option>
            <option value="تفسير" {{ request('category') == 'تفسير' ? 'selected' : '' }}>التفسير</option>
            <option value="حديث" {{ request('category') == 'حديث' ? 'selected' : '' }}>الحديث الشريف</option>
            <option value="فقه" {{ request('category') == 'فقه' ? 'selected' : '' }}>الفقه</option>
            <option value="دعوة" {{ request('category') == 'دعوة' ? 'selected' : '' }}>الدعوة</option>
            <option value="مناسبات" {{ request('category') == 'مناسبات' ? 'selected' : '' }}>المناسبات</option>
        </select>
        
        <button type="submit" class="search-btn">
            <i class="fas fa-search me-2"></i>
            بحث
        </button>
    </form>
</div>

{{-- قائمة الخطب --}}
@if($sermons->count() > 0)
    <div class="sermons-grid">
        @foreach($sermons as $sermon)
            <div class="sermon-card">
                <div class="sermon-header">
                    <div class="sermon-category">{{ $sermon->category }}</div>
                    <h3 class="sermon-title">
                        <a href="{{ route('sermons.show', $sermon->id) }}">
                            {{ $sermon->title }}
                        </a>
                    </h3>
                    <div class="sermon-author">
                        <i class="fas fa-user"></i>
                        {{ $sermon->author->name ?? 'غير محدد' }}
                    </div>
                </div>
                
                <div class="sermon-body">
                    <p class="sermon-excerpt">
                        {{ Str::limit(strip_tags($sermon->introduction), 150) }}
                    </p>
                    
                    <div class="sermon-meta">
                        <div class="sermon-stats">
                            <span><i class="fas fa-eye"></i> {{ number_format($sermon->views_count) }}</span>
                            <span><i class="fas fa-download"></i> {{ number_format($sermon->downloads_count) }}</span>
                        </div>
                        <div class="sermon-date">
                            {{ $sermon->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{-- Pagination --}}
    <div class="pagination-wrapper">
        {{ $sermons->appends(request()->query())->links() }}
    </div>
@else
    <div class="no-sermons">
        <i class="fas fa-search"></i>
        <h3>لم يتم العثور على خطب</h3>
        <p>جرب تغيير معايير البحث أو تصفح جميع الخطب</p>
        <a href="{{ route('sermons.index') }}" class="btn btn-primary mt-3">
            <i class="fas fa-list"></i>
            عرض جميع الخطب
        </a>
    </div>
@endif

{{-- رابط إعداد خطبة جديدة --}}
<div class="text-center mt-5">
    <a href="{{ route('sermons.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-plus me-2"></i>
        إعداد خطبة جديدة
    </a>
</div>
@endsection
