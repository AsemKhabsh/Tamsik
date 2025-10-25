@extends('scholar.layout')

@section('title', 'الأسئلة')
@section('page-title', 'إدارة الأسئلة')
@section('page-description', 'عرض وإدارة الأسئلة الموجهة لك')

@section('content')
<!-- Statistics Summary -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card {{ $status == 'all' ? 'primary' : '' }}" style="cursor: pointer;" onclick="window.location='{{ route('scholar.questions.index') }}'">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ $stats['total'] }}</h4>
                    <small class="text-muted">جميع الأسئلة</small>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card {{ $status == 'pending' ? 'warning' : '' }}" style="cursor: pointer;" onclick="window.location='{{ route('scholar.questions.index', ['status' => 'pending']) }}'">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                    <small class="text-muted">قيد الانتظار</small>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card {{ $status == 'answered' ? 'success' : '' }}" style="cursor: pointer;" onclick="window.location='{{ route('scholar.questions.index', ['status' => 'answered']) }}'">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ $stats['answered'] }}</h4>
                    <small class="text-muted">تم الرد عليها</small>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card {{ $status == 'draft' ? 'info' : '' }}" style="cursor: pointer;" onclick="window.location='{{ route('scholar.questions.index', ['status' => 'draft']) }}'">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">{{ $stats['draft'] }}</h4>
                    <small class="text-muted">المسودات</small>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="{{ route('scholar.questions.index') }}">
                    <i class="fas fa-list me-1"></i>
                    الكل
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="{{ route('scholar.questions.index', ['status' => 'pending']) }}">
                    <i class="fas fa-clock me-1"></i>
                    قيد الانتظار
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'answered' ? 'active' : '' }}" href="{{ route('scholar.questions.index', ['status' => 'answered']) }}">
                    <i class="fas fa-check-circle me-1"></i>
                    تم الرد عليها
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'draft' ? 'active' : '' }}" href="{{ route('scholar.questions.index', ['status' => 'draft']) }}">
                    <i class="fas fa-file-alt me-1"></i>
                    المسودات
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Questions List -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">
            @if($status == 'pending')
                <i class="fas fa-clock text-warning me-2"></i>
                الأسئلة قيد الانتظار
            @elseif($status == 'answered')
                <i class="fas fa-check-circle text-success me-2"></i>
                الأسئلة المجابة
            @elseif($status == 'draft')
                <i class="fas fa-file-alt text-info me-2"></i>
                المسودات
            @else
                <i class="fas fa-list me-2"></i>
                جميع الأسئلة
            @endif
            <span class="badge bg-secondary">{{ $questions->total() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @forelse($questions as $question)
            <div class="question-card">
                <div class="row">
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="mb-0">{{ $question->title }}</h5>
                            @if($question->is_published)
                                <span class="badge bg-success badge-status">منشور</span>
                            @elseif(!empty($question->answer))
                                <span class="badge bg-info badge-status">مسودة</span>
                            @else
                                <span class="badge bg-warning text-dark badge-status">قيد الانتظار</span>
                            @endif
                        </div>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $question->category }}</span>
                        </div>
                        
                        <p class="text-muted mb-2">
                            <strong>السؤال:</strong> {{ Str::limit($question->question, 200) }}
                        </p>
                        
                        @if(!empty($question->answer))
                            <p class="text-muted mb-2">
                                <strong>الإجابة:</strong> {{ Str::limit($question->answer, 150) }}
                            </p>
                        @endif
                        
                        <div class="d-flex align-items-center text-muted small">
                            <span class="me-3">
                                <i class="fas fa-user me-1"></i>
                                {{ $question->questioner ? $question->questioner->name : 'مجهول' }}
                            </span>
                            <span class="me-3">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $question->created_at->format('Y-m-d') }}
                            </span>
                            @if($question->is_published)
                                <span class="me-3">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ $question->views_count ?? 0 }} مشاهدة
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-end">
                        <div class="d-flex flex-column gap-2">
                            @if(empty($question->answer))
                                <a href="{{ route('scholar.questions.show', $question->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-reply me-1"></i>
                                    الرد على السؤال
                                </a>
                            @else
                                <a href="{{ route('scholar.questions.show', $question->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit me-1"></i>
                                    تعديل الإجابة
                                </a>
                                
                                @if($question->is_published)
                                    <a href="{{ route('fatwas.show', $question->id) }}" class="btn btn-outline-info btn-sm" target="_blank">
                                        <i class="fas fa-eye me-1"></i>
                                        عرض
                                    </a>
                                    <form action="{{ route('scholar.questions.unpublish', $question->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-warning btn-sm w-100" onclick="return confirm('هل تريد إلغاء نشر هذه الإجابة؟')">
                                            <i class="fas fa-eye-slash me-1"></i>
                                            إلغاء النشر
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('scholar.questions.publish', $question->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-success btn-sm w-100" onclick="return confirm('هل تريد نشر هذه الإجابة؟')">
                                            <i class="fas fa-check me-1"></i>
                                            نشر الإجابة
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد أسئلة</h5>
                <p class="text-muted">
                    @if($status == 'pending')
                        لا توجد أسئلة قيد الانتظار حالياً
                    @elseif($status == 'answered')
                        لم تقم بالرد على أي أسئلة بعد
                    @elseif($status == 'draft')
                        لا توجد مسودات محفوظة
                    @else
                        لا توجد أسئلة موجهة لك حالياً
                    @endif
                </p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($questions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $questions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

