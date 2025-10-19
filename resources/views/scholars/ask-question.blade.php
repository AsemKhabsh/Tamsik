@extends('layouts.app')

@section('title', 'اطرح سؤالاً - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="section-title">
                <i class="fas fa-question-circle me-2"></i>
                اطرح سؤالاً للعلماء
            </h1>
            <p class="text-muted">اطرح أسئلتك الشرعية واحصل على إجابات من علماء موثوقين</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Question Form -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        نموذج طرح السؤال
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('scholars.submit-question') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="scholar_id" class="form-label">
                                    <i class="fas fa-user-graduate me-1"></i>
                                    العالم المختص (اختياري)
                                </label>
                                <select name="scholar_id" id="scholar_id" class="form-select">
                                    <option value="">أي عالم متاح</option>
                                    @foreach($scholars as $scholar)
                                        <option value="{{ $scholar->id }}">{{ $scholar->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">يمكنك اختيار عالم محدد أو ترك الخيار مفتوحاً</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-tags me-1"></i>
                                    تصنيف السؤال *
                                </label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">اختر التصنيف</option>
                                    <option value="worship">العبادات</option>
                                    <option value="transactions">المعاملات</option>
                                    <option value="family">الأسرة والزواج</option>
                                    <option value="contemporary">القضايا المعاصرة</option>
                                    <option value="ethics">الأخلاق والآداب</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">
                                <i class="fas fa-heading me-1"></i>
                                موضوع السؤال *
                            </label>
                            <input type="text" name="subject" id="subject" class="form-control" 
                                   placeholder="اكتب موضوع سؤالك بشكل مختصر" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label for="question" class="form-label">
                                <i class="fas fa-question me-1"></i>
                                نص السؤال *
                            </label>
                            <textarea name="question" id="question" class="form-control" rows="6" 
                                      placeholder="اكتب سؤالك بالتفصيل..." required maxlength="2000"></textarea>
                            <small class="text-muted">الحد الأقصى 2000 حرف</small>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_public" id="is_public" value="1">
                                <label class="form-check-label" for="is_public">
                                    <i class="fas fa-globe me-1"></i>
                                    أوافق على نشر السؤال والإجابة للاستفادة العامة
                                </label>
                                <small class="d-block text-muted mt-1">
                                    إذا لم تختر هذا الخيار، ستكون الإجابة خاصة بك فقط
                                </small>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                إرسال السؤال
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Guidelines -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        إرشادات مهمة
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            اكتب سؤالك بوضوح ودقة
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            اختر التصنيف المناسب لسؤالك
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            تجنب الأسئلة الشخصية المحرجة
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            انتظر الرد خلال 24-48 ساعة
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            ستصلك الإجابة عبر البريد الإلكتروني
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Available Scholars -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        العلماء المتاحون
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($scholars as $scholar)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0">{{ $scholar->name }}</h6>
                                <small class="text-muted">عالم معتمد</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">لا يوجد علماء متاحون حالياً</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(44, 85, 48, 0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary:hover {
    background-color: var(--secondary-color);
    border-color: var(--secondary-color);
}
</style>
@endsection
