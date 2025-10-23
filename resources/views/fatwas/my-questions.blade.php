@extends('layouts.app')

@section('title', 'أسئلتي')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="page-header mb-5">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2" style="color: #1d8a4e;">
                    <i class="fas fa-list me-2"></i>
                    أسئلتي
                </h1>
                <p class="text-muted mb-0">تتبع حالة أسئلتك والإجابات عليها</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('questions.ask') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>
                    اطرح سؤالاً جديداً
                </a>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    @if($questions->count() > 0)
        <div class="questions-list">
            @foreach($questions as $question)
                <div class="question-card {{ $question->is_published ? 'answered' : 'pending' }}">
                    <div class="question-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="question-title">{{ $question->title }}</h3>
                                <div class="question-meta">
                                    <span class="badge bg-{{ $question->is_published ? 'success' : 'warning' }}">
                                        <i class="fas fa-{{ $question->is_published ? 'check-circle' : 'clock' }} me-1"></i>
                                        {{ $question->is_published ? 'تم الرد' : 'قيد المراجعة' }}
                                    </span>
                                    <span class="category-badge">{{ $question->category }}</span>
                                    <span class="date-badge">
                                        <i class="fas fa-calendar"></i>
                                        {{ $question->created_at->format('d/m/Y') }}
                                    </span>
                                    @if($question->scholar)
                                        <span class="scholar-badge">
                                            <i class="fas fa-user-graduate"></i>
                                            {{ $question->scholar->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="question-body">
                        <div class="question-section">
                            <h5 class="section-label">
                                <i class="fas fa-question-circle me-2"></i>
                                السؤال:
                            </h5>
                            <p class="section-text">{{ Str::limit($question->question, 200) }}</p>
                        </div>

                        @if($question->answer)
                            <div class="answer-section">
                                <h5 class="section-label">
                                    <i class="fas fa-check-circle me-2"></i>
                                    الجواب:
                                </h5>
                                <p class="section-text">{{ Str::limit($question->answer, 200) }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="question-footer">
                        @if($question->is_published && $question->answer)
                            <a href="{{ route('fatwas.show', $question->id) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-eye me-2"></i>
                                عرض الإجابة الكاملة
                            </a>
                        @else
                            <span class="text-muted">
                                <i class="fas fa-hourglass-half me-2"></i>
                                في انتظار الرد من العالم
                            </span>
                        @endif
                        
                        @if($question->views_count > 0)
                            <span class="views-count">
                                <i class="fas fa-eye"></i>
                                {{ number_format($question->views_count) }} مشاهدة
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($questions->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $questions->links() }}
            </div>
        @endif
    @else
        <div class="empty-state text-center py-5">
            <i class="fas fa-inbox mb-3" style="font-size: 5rem; color: #ccc;"></i>
            <h3 class="text-muted mb-3">لم تطرح أي أسئلة بعد</h3>
            <p class="text-muted mb-4">ابدأ بطرح سؤالك الأول وسيجيب عليه أحد علمائنا الأفاضل</p>
            <a href="{{ route('questions.ask') }}" class="btn btn-success btn-lg">
                <i class="fas fa-question-circle me-2"></i>
                اطرح سؤالك الأول
            </a>
        </div>
    @endif
</div>

<style>
.question-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    overflow: hidden;
    border-right: 5px solid #ccc;
    transition: all 0.3s ease;
}

.question-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.question-card.answered {
    border-right-color: #1d8a4e;
}

.question-card.pending {
    border-right-color: #ffc107;
}

.question-header {
    padding: 25px 25px 15px 25px;
    border-bottom: 1px solid #eee;
}

.question-title {
    color: #1d8a4e;
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.question-meta {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.category-badge, .date-badge, .scholar-badge {
    background: #f8f9fa;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    color: #666;
}

.question-body {
    padding: 25px;
}

.question-section, .answer-section {
    margin-bottom: 20px;
}

.answer-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-right: 3px solid #1d8a4e;
}

.section-label {
    color: #1d8a4e;
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.section-text {
    color: #555;
    line-height: 1.8;
    margin: 0;
}

.question-footer {
    padding: 15px 25px;
    background: #f8f9fa;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.views-count {
    color: #666;
    font-size: 0.9rem;
}

.empty-state {
    background: white;
    border-radius: 10px;
    padding: 60px 20px;
}
</style>
@endsection

