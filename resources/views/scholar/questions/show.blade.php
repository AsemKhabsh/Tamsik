@extends('scholar.layout')

@section('title', 'الرد على السؤال')
@section('page-title', empty($question->answer) ? 'الرد على السؤال' : 'تعديل الإجابة')
@section('page-description', $question->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Question Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle text-primary me-2"></i>
                    تفاصيل السؤال
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">عنوان السؤال:</label>
                    <h5>{{ $question->title }}</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">التصنيف:</label>
                    <br>
                    <span class="badge bg-secondary">{{ $question->category }}</span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">نص السؤال:</label>
                    <div class="p-3 bg-light rounded">
                        {!! nl2br(e($question->question)) !!}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">السائل:</label>
                    <p class="mb-0">
                        <i class="fas fa-user me-1"></i>
                        {{ $question->questioner ? $question->questioner->name : 'مجهول' }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">تاريخ السؤال:</label>
                    <p class="mb-0">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $question->created_at->format('Y-m-d H:i') }}
                        ({{ $question->created_at->diffForHumans() }})
                    </p>
                </div>
            </div>
        </div>

        <!-- Answer Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h5 class="mb-0">
                    <i class="fas fa-reply text-success me-2"></i>
                    {{ empty($question->answer) ? 'كتابة الإجابة' : 'تعديل الإجابة' }}
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ empty($question->answer) ? route('scholar.questions.answer', $question->id) : route('scholar.questions.update', $question->id) }}" 
                      method="POST">
                    @csrf
                    @method(empty($question->answer) ? 'POST' : 'PUT')

                    <div class="mb-3">
                        <label for="answer" class="form-label fw-bold">
                            نص الإجابة <span class="text-danger">*</span>
                        </label>
                        <textarea name="answer" 
                                  id="answer" 
                                  class="form-control @error('answer') is-invalid @enderror" 
                                  rows="10" 
                                  required
                                  placeholder="اكتب إجابتك هنا... (الحد الأدنى 50 حرفاً)">{{ old('answer', $question->answer) }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">الحد الأدنى: 50 حرفاً</small>
                    </div>

                    <div class="mb-3">
                        <label for="references" class="form-label fw-bold">
                            المراجع والمصادر
                        </label>
                        <textarea name="references" 
                                  id="references" 
                                  class="form-control @error('references') is-invalid @enderror" 
                                  rows="4"
                                  placeholder="أدخل المراجع (كل مرجع في سطر منفصل)">{{ old('references', is_array($question->references) ? implode("\n", $question->references) : '') }}</textarea>
                        @error('references')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">أدخل كل مرجع في سطر منفصل</small>
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label fw-bold">
                            الوسوم (Tags)
                        </label>
                        <input type="text" 
                               name="tags" 
                               id="tags" 
                               class="form-control @error('tags') is-invalid @enderror" 
                               value="{{ old('tags', is_array($question->tags) ? implode(', ', $question->tags) : '') }}"
                               placeholder="مثال: صلاة, زكاة, صيام">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">افصل بين الوسوم بفاصلة</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_published" 
                                   id="is_published" 
                                   value="1"
                                   {{ old('is_published', $question->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_published">
                                نشر الإجابة مباشرة
                            </label>
                            <br>
                            <small class="text-muted">إذا لم تحدد هذا الخيار، سيتم حفظ الإجابة كمسودة</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>
                            {{ empty($question->answer) ? 'حفظ الإجابة' : 'تحديث الإجابة' }}
                        </button>
                        
                        <a href="{{ route('scholar.questions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    حالة السؤال
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">الحالة الحالية:</label>
                    <br>
                    @if($question->is_published)
                        <span class="badge bg-success">منشور</span>
                    @elseif(!empty($question->answer))
                        <span class="badge bg-info">مسودة</span>
                    @else
                        <span class="badge bg-warning text-dark">قيد الانتظار</span>
                    @endif
                </div>

                @if($question->is_published && $question->published_at)
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ النشر:</label>
                        <p class="mb-0 small">{{ $question->published_at->format('Y-m-d H:i') }}</p>
                    </div>
                @endif

                @if($question->is_published)
                    <div class="mb-3">
                        <label class="form-label fw-bold">المشاهدات:</label>
                        <p class="mb-0">
                            <i class="fas fa-eye me-1"></i>
                            {{ $question->views_count ?? 0 }} مشاهدة
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    إجراءات سريعة
                </h6>
            </div>
            <div class="card-body">
                @if($question->is_published)
                    <a href="{{ route('fatwas.show', $question->id) }}" class="btn btn-outline-info btn-sm w-100 mb-2" target="_blank">
                        <i class="fas fa-eye me-1"></i>
                        عرض الفتوى المنشورة
                    </a>
                    
                    <form action="{{ route('scholar.questions.unpublish', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-warning btn-sm w-100 mb-2" onclick="return confirm('هل تريد إلغاء نشر هذه الإجابة؟')">
                            <i class="fas fa-eye-slash me-1"></i>
                            إلغاء النشر
                        </button>
                    </form>
                @elseif(!empty($question->answer))
                    <form action="{{ route('scholar.questions.publish', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-outline-success btn-sm w-100 mb-2" onclick="return confirm('هل تريد نشر هذه الإجابة؟')">
                            <i class="fas fa-check me-1"></i>
                            نشر الإجابة
                        </button>
                    </form>
                @endif

                <a href="{{ route('scholar.questions.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-arrow-right me-1"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>

        <!-- Help Card -->
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="fas fa-lightbulb text-warning me-2"></i>
                    نصائح للإجابة
                </h6>
                <ul class="small mb-0">
                    <li class="mb-2">اقرأ السؤال بعناية قبل الإجابة</li>
                    <li class="mb-2">استخدم لغة واضحة ومفهومة</li>
                    <li class="mb-2">أضف المراجع الشرعية عند الحاجة</li>
                    <li class="mb-2">راجع إجابتك قبل النشر</li>
                    <li>يمكنك حفظ الإجابة كمسودة والعودة لها لاحقاً</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for answer
    const answerTextarea = document.getElementById('answer');
    if (answerTextarea) {
        answerTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const minLength = 50;
            
            if (length < minLength) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    }
</script>
@endpush

