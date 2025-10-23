@extends('layouts.app')

@section('title', 'اطرح سؤالاً')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="text-center mb-5">
                <h1 class="mb-3" style="color: #1d8a4e;">
                    <i class="fas fa-question-circle me-2"></i>
                    اطرح سؤالك الشرعي
                </h1>
                <p class="lead text-muted">سيجيب على سؤالك أحد علمائنا الأفاضل</p>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('questions.store') }}" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-2"></i>
                            عنوان السؤال <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               placeholder="مثال: ما حكم صلاة الجمعة؟"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label for="category" class="form-label">
                            <i class="fas fa-tag me-2"></i>
                            التصنيف <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category" 
                                required>
                            <option value="">اختر التصنيف المناسب</option>
                            <option value="worship" {{ old('category') == 'worship' ? 'selected' : '' }}>العبادات</option>
                            <option value="transactions" {{ old('category') == 'transactions' ? 'selected' : '' }}>المعاملات</option>
                            <option value="family" {{ old('category') == 'family' ? 'selected' : '' }}>الأسرة</option>
                            <option value="contemporary" {{ old('category') == 'contemporary' ? 'selected' : '' }}>القضايا المعاصرة</option>
                            <option value="ethics" {{ old('category') == 'ethics' ? 'selected' : '' }}>الأخلاق والآداب</option>
                            <option value="beliefs" {{ old('category') == 'beliefs' ? 'selected' : '' }}>العقيدة</option>
                            <option value="jurisprudence" {{ old('category') == 'jurisprudence' ? 'selected' : '' }}>الفقه</option>
                            <option value="quran" {{ old('category') == 'quran' ? 'selected' : '' }}>القرآن الكريم</option>
                            <option value="hadith" {{ old('category') == 'hadith' ? 'selected' : '' }}>الحديث الشريف</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Scholar (Optional) -->
                    <div class="mb-4">
                        <label for="scholar_id" class="form-label">
                            <i class="fas fa-user-graduate me-2"></i>
                            توجيه السؤال لعالم محدد (اختياري)
                        </label>
                        <select class="form-select @error('scholar_id') is-invalid @enderror" 
                                id="scholar_id" 
                                name="scholar_id">
                            <option value="">أي عالم متاح</option>
                            @foreach($scholars as $scholar)
                                <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>
                                    {{ $scholar->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('scholar_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            إذا لم تحدد عالماً، سيتم توجيه السؤال لأول عالم متاح
                        </small>
                    </div>

                    <!-- Question -->
                    <div class="mb-4">
                        <label for="question" class="form-label">
                            <i class="fas fa-question me-2"></i>
                            نص السؤال <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control @error('question') is-invalid @enderror" 
                                  id="question" 
                                  name="question" 
                                  rows="8"
                                  placeholder="اكتب سؤالك بالتفصيل..."
                                  required>{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            يرجى كتابة السؤال بوضوح وتفصيل لضمان الحصول على إجابة دقيقة
                        </small>
                    </div>

                    <!-- Guidelines -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>
                            إرشادات طرح السؤال:
                        </h6>
                        <ul class="mb-0">
                            <li>اكتب سؤالك بوضوح ودقة</li>
                            <li>اختر التصنيف المناسب لسؤالك</li>
                            <li>تجنب الأسئلة المكررة</li>
                            <li>احترم آداب الحوار الإسلامي</li>
                            <li>سيتم مراجعة السؤال قبل نشره</li>
                        </ul>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('fatwas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-2"></i>
                            إرسال السؤال
                        </button>
                    </div>
                </form>
            </div>

            <!-- My Questions Link -->
            <div class="text-center mt-4">
                <a href="{{ route('questions.my') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>
                    أسئلتي السابقة
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.form-card {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 1rem;
}

.form-control:focus, .form-select:focus {
    border-color: #1d8a4e;
    box-shadow: 0 0 0 0.2rem rgba(29, 138, 78, 0.25);
}

.btn-success {
    background: #1d8a4e;
    border-color: #1d8a4e;
    padding: 12px 30px;
    font-weight: 600;
}

.btn-success:hover {
    background: #0f7346;
    border-color: #0f7346;
}

.alert-info {
    background: #e7f3ff;
    border-color: #b3d9ff;
    color: #004085;
}

.alert-info ul {
    padding-right: 20px;
    margin-top: 10px;
}

.alert-info li {
    margin-bottom: 5px;
}
</style>
@endsection

