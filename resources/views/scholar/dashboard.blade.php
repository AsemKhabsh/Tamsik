@extends('scholar.layout')

@section('title', 'لوحة تحكم العالم')
@section('page-title', 'مرحباً بك، ' . Auth::user()->name)
@section('page-description', 'إدارة الأسئلة والفتاوى الموجهة لك')

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    <small class="text-muted">إجمالي الأسئلة</small>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                    <small class="text-muted">قيد الانتظار</small>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['answered'] }}</h3>
                    <small class="text-muted">تم الرد عليها</small>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">{{ $stats['draft'] }}</h3>
                    <small class="text-muted">المسودات</small>
                </div>
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Questions -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-warning me-2"></i>
                    أسئلة قيد الانتظار
                </h5>
                <a href="{{ route('scholar.questions.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @forelse($pendingQuestions as $question)
                    <div class="question-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ Str::limit($question->title, 60) }}</h6>
                            <span class="badge bg-warning text-dark badge-status">قيد الانتظار</span>
                        </div>
                        <p class="text-muted small mb-2">
                            {{ Str::limit($question->question, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $question->questioner ? $question->questioner->name : 'مجهول' }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-calendar me-1"></i>
                                {{ $question->created_at->diffForHumans() }}
                            </small>
                            <a href="{{ route('scholar.questions.show', $question->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-reply me-1"></i>
                                الرد
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد أسئلة قيد الانتظار</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Answers -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    آخر الإجابات المنشورة
                </h5>
                <a href="{{ route('scholar.questions.index', ['status' => 'answered']) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                @forelse($recentAnswers as $question)
                    <div class="question-card">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ Str::limit($question->title, 60) }}</h6>
                            <span class="badge bg-success badge-status">منشور</span>
                        </div>
                        <p class="text-muted small mb-2">
                            {{ Str::limit($question->question, 100) }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                نُشر {{ $question->published_at ? $question->published_at->diffForHumans() : 'مؤخراً' }}
                                <span class="mx-2">|</span>
                                <i class="fas fa-eye me-1"></i>
                                {{ $question->views_count ?? 0 }} مشاهدة
                            </small>
                            <div>
                                <a href="{{ route('fatwas.show', $question->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="fas fa-eye me-1"></i>
                                    عرض
                                </a>
                                <a href="{{ route('scholar.questions.show', $question->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i>
                                    تعديل
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد إجابات منشورة بعد</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-primary me-2"></i>
                    إجراءات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('scholar.questions.index', ['status' => 'pending']) }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <br>
                            الرد على الأسئلة
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('scholar.questions.index', ['status' => 'draft']) }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <br>
                            المسودات
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('scholar.questions.index') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <br>
                            جميع الأسئلة
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('fatwas.scholar', Auth::id()) }}" class="btn btn-outline-success w-100 py-3" target="_blank">
                            <i class="fas fa-eye fa-2x mb-2"></i>
                            <br>
                            عرض فتاواي
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

