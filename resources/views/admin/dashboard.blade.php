@extends('admin.layout')

@section('title', 'لوحة التحكم الرئيسية')
@section('page-title', 'لوحة التحكم')
@section('page-description', 'نظرة عامة على إحصائيات الموقع والأنشطة الحديثة')

@section('content')
<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-muted mb-0">إجمالي المستخدمين</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0">{{ number_format($stats['total_sermons']) }}</h3>
                    <p class="text-muted mb-0">إجمالي الخطب</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="fas fa-microphone"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0">{{ number_format($stats['total_lectures']) }}</h3>
                    <p class="text-muted mb-0">إجمالي المحاضرات</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="d-flex align-items-center">
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="ms-3">
                    <h3 class="mb-0">{{ number_format($stats['total_articles']) }}</h3>
                    <p class="text-muted mb-0">إجمالي المقالات</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- تنبيه المقالات المعلقة -->
@if(isset($stats['pending_articles']) && $stats['pending_articles'] > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-1">مقالات تحتاج إلى مراجعة!</h5>
                <p class="mb-0">
                    يوجد <strong>{{ $stats['pending_articles'] }}</strong> مقال(ات) قيد المراجعة تحتاج إلى موافقتك.
                </p>
            </div>
            <a href="{{ route('admin.articles.pending') }}" class="btn btn-warning">
                <i class="fas fa-eye me-2"></i>
                مراجعة المقالات
            </a>
        </div>
    </div>
</div>
@endif

<!-- إحصائيات الخطب -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">
                <i class="fas fa-chart-pie text-success"></i>
                إحصائيات الخطب
            </h5>
            <div class="row">
                <div class="col-6">
                    <div class="text-center">
                        <h4 class="text-success">{{ number_format($stats['published_sermons']) }}</h4>
                        <p class="text-muted mb-0">خطب منشورة</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="text-center">
                        <h4 class="text-warning">{{ number_format($stats['draft_sermons']) }}</h4>
                        <p class="text-muted mb-0">مسودات</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">
                <i class="fas fa-plus-circle text-primary"></i>
                إضافة سريعة
            </h5>
            <div class="d-grid gap-2">
                <a href="{{ route('sermons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    إضافة خطبة جديدة
                </a>
                <a href="{{ route('lectures.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                    إضافة محاضرة جديدة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- الأنشطة الحديثة -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">
                <i class="fas fa-clock text-info"></i>
                المستخدمين الجدد
            </h5>
            @if($stats['recent_users']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['recent_users'] as $user)
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div>
                            <h6 class="mb-1">{{ $user->name }}</h6>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'scholar' ? 'success' : 'secondary') }}">
                                {{ $user->role }}
                            </span>
                            <small class="text-muted d-block">{{ $user->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">
                        عرض جميع المستخدمين
                    </a>
                </div>
            @else
                <p class="text-muted text-center">لا توجد مستخدمين جدد</p>
            @endif
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="stats-card">
            <h5 class="mb-3">
                <i class="fas fa-book text-success"></i>
                الخطب الحديثة
            </h5>
            @if($stats['recent_sermons']->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($stats['recent_sermons'] as $sermon)
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                        <div>
                            <h6 class="mb-1">{{ Str::limit($sermon->title, 30) }}</h6>
                            <small class="text-muted">{{ $sermon->author->name ?? 'غير محدد' }}</small>
                        </div>
                        <div>
                            <span class="badge bg-{{ $sermon->is_published ? 'success' : 'warning' }}">
                                {{ $sermon->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                            <small class="text-muted d-block">{{ $sermon->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.sermons') }}" class="btn btn-outline-success btn-sm">
                        عرض جميع الخطب
                    </a>
                </div>
            @else
                <p class="text-muted text-center">لا توجد خطب حديثة</p>
            @endif
        </div>
    </div>
</div>

<!-- المحاضرات الحديثة -->
<div class="row">
    <div class="col-12">
        <div class="stats-card">
            <h5 class="mb-3">
                <i class="fas fa-microphone text-primary"></i>
                المحاضرات الحديثة
            </h5>
            @if($stats['recent_lectures']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المحاضر</th>
                                <th>التاريخ المجدول</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recent_lectures'] as $lecture)
                            <tr>
                                <td>{{ Str::limit($lecture->title, 40) }}</td>
                                <td>{{ $lecture->speaker->name ?? 'غير محدد' }}</td>
                                <td>{{ $lecture->scheduled_at ? $lecture->scheduled_at->format('d/m/Y H:i') : 'غير محدد' }}</td>
                                <td>
                                    <span class="badge bg-{{ $lecture->status == 'completed' ? 'success' : ($lecture->status == 'scheduled' ? 'primary' : 'secondary') }}">
                                        {{ $lecture->status == 'completed' ? 'مكتملة' : ($lecture->status == 'scheduled' ? 'مجدولة' : 'ملغية') }}
                                    </span>
                                </td>
                                <td>{{ $lecture->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.lectures') }}" class="btn btn-outline-primary">
                        عرض جميع المحاضرات
                    </a>
                </div>
            @else
                <p class="text-muted text-center">لا توجد محاضرات حديثة</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تحديث الإحصائيات كل 30 ثانية
    setInterval(function() {
        // يمكن إضافة AJAX لتحديث الإحصائيات
    }, 30000);
</script>
@endpush
