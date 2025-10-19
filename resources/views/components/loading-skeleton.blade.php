{{--
    مكون Skeleton Loader لعرض حالة التحميل
    
    الاستخدام:
    @include('components.loading-skeleton', ['type' => 'card', 'count' => 3])
    
    الأنواع المتاحة:
    - card: بطاقة محتوى
    - list: قائمة عناصر
    - text: نص فقط
    - profile: ملف شخصي
    - table: جدول
--}}

@php
    $type = $type ?? 'card';
    $count = $count ?? 1;
@endphp

<style>
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s ease-in-out infinite;
        border-radius: 4px;
    }
    
    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    .skeleton-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .skeleton-image {
        width: 100%;
        height: 200px;
        margin-bottom: 15px;
    }
    
    .skeleton-title {
        height: 24px;
        width: 70%;
        margin-bottom: 10px;
    }
    
    .skeleton-text {
        height: 16px;
        width: 100%;
        margin-bottom: 8px;
    }
    
    .skeleton-text.short {
        width: 60%;
    }
    
    .skeleton-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-left: 15px;
    }
    
    .skeleton-list-item {
        display: flex;
        align-items: center;
        padding: 15px;
        background: white;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    
    .skeleton-table-row {
        display: flex;
        gap: 15px;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .skeleton-table-cell {
        height: 20px;
        flex: 1;
    }
</style>

@if($type === 'card')
    @for($i = 0; $i < $count; $i++)
        <div class="skeleton-card">
            <div class="skeleton skeleton-image"></div>
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
        </div>
    @endfor
@endif

@if($type === 'list')
    @for($i = 0; $i < $count; $i++)
        <div class="skeleton-list-item">
            <div class="skeleton skeleton-avatar"></div>
            <div style="flex: 1;">
                <div class="skeleton skeleton-title" style="width: 40%; margin-bottom: 8px;"></div>
                <div class="skeleton skeleton-text" style="width: 80%;"></div>
            </div>
        </div>
    @endfor
@endif

@if($type === 'text')
    @for($i = 0; $i < $count; $i++)
        <div class="skeleton skeleton-text" style="margin-bottom: 10px;"></div>
    @endfor
@endif

@if($type === 'profile')
    <div class="skeleton-card">
        <div style="display: flex; align-items: center; margin-bottom: 20px;">
            <div class="skeleton" style="width: 100px; height: 100px; border-radius: 50%; margin-left: 20px;"></div>
            <div style="flex: 1;">
                <div class="skeleton skeleton-title" style="width: 50%; margin-bottom: 10px;"></div>
                <div class="skeleton skeleton-text" style="width: 70%;"></div>
            </div>
        </div>
        <div class="skeleton skeleton-text"></div>
        <div class="skeleton skeleton-text"></div>
        <div class="skeleton skeleton-text short"></div>
    </div>
@endif

@if($type === 'table')
    <div class="skeleton-card">
        @for($i = 0; $i < $count; $i++)
            <div class="skeleton-table-row">
                <div class="skeleton skeleton-table-cell"></div>
                <div class="skeleton skeleton-table-cell"></div>
                <div class="skeleton skeleton-table-cell"></div>
                <div class="skeleton skeleton-table-cell"></div>
            </div>
        @endfor
    </div>
@endif

