@extends('layouts.app')

@section('title', 'خطبي - تمسك')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary mb-1">
                        <i class="fas fa-book-open me-2"></i>
                        خطبي
                    </h2>
                    <p class="text-muted">إدارة الخطب التي قمت بإنشائها</p>
                </div>
                <a href="{{ route('sermons.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة خطبة جديدة
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Sermons List -->
            @if($sermons->count() > 0)
                <div class="row">
                    @foreach($sermons as $sermon)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                @if($sermon->featured_image)
                                    <img src="{{ asset('storage/' . $sermon->featured_image) }}" 
                                         class="card-img-top" style="height: 200px; object-fit: cover;" 
                                         alt="{{ $sermon->title }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fas fa-book-open fa-3x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $sermon->title }}</h5>
                                    
                                    <div class="mb-2">
                                        <span class="badge bg-secondary">{{ $sermon->category }}</span>
                                        @if($sermon->status == 'published')
                                            <span class="badge bg-success">منشورة</span>
                                        @elseif($sermon->status == 'draft')
                                            <span class="badge bg-warning">مسودة</span>
                                        @elseif($sermon->status == 'pending')
                                            <span class="badge bg-info">قيد المراجعة</span>
                                        @else
                                            <span class="badge bg-danger">مرفوضة</span>
                                        @endif
                                    </div>

                                    <p class="card-text text-muted small flex-grow-1">
                                        {{ \Str::limit($sermon->introduction, 100) }}
                                    </p>

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $sermon->created_at->format('d/m/Y') }}
                                            </small>
                                            @if($sermon->views_count > 0)
                                                <small class="text-muted">
                                                    <i class="fas fa-eye me-1"></i>
                                                    {{ $sermon->views_count }} مشاهدة
                                                </small>
                                            @endif
                                        </div>

                                        <div class="btn-group w-100" role="group">
                                            @if($sermon->is_published)
                                                <a href="{{ route('sermons.show', $sermon->id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>
                                                    عرض
                                                </a>
                                            @endif
                                            <a href="{{ route('sermons.edit', $sermon->id) }}" 
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-edit me-1"></i>
                                                تعديل
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $sermons->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-book-open fa-5x text-muted"></i>
                    </div>
                    <h4 class="text-muted mb-3">لم تقم بإنشاء أي خطب بعد</h4>
                    <p class="text-muted mb-4">ابدأ بإنشاء خطبتك الأولى وشاركها مع المجتمع</p>
                    <a href="{{ route('sermons.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء خطبة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
