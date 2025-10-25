@extends('admin.layout')

@section('title', 'إضافة فتوى جديدة')
@section('page-title', 'إضافة فتوى جديدة')
@section('page-description', 'إضافة فتوى جديدة إلى الموقع')

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus-circle"></i>
                    إضافة فتوى جديدة
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.fatwas.store') }}" method="POST">
                    @csrf

                    <!-- العنوان -->
                    <div class="mb-3">
                        <label for="title" class="form-label">عنوان الفتوى <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('title') is-invalid @enderror" 
                            id="title" 
                            name="title" 
                            value="{{ old('title') }}" 
                            required
                            placeholder="أدخل عنوان الفتوى">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">عنوان مختصر وواضح للفتوى</small>
                    </div>

                    <!-- السؤال -->
                    <div class="mb-3">
                        <label for="question" class="form-label">السؤال <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('question') is-invalid @enderror" 
                            id="question" 
                            name="question" 
                            rows="5" 
                            required
                            placeholder="أدخل نص السؤال">{{ old('question') }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- الإجابة -->
                    <div class="mb-3">
                        <label for="answer" class="form-label">الإجابة <span class="text-danger">*</span></label>
                        <textarea 
                            class="form-control @error('answer') is-invalid @enderror" 
                            id="answer" 
                            name="answer" 
                            rows="10" 
                            required
                            placeholder="أدخل إجابة العالم على السؤال">{{ old('answer') }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- العالم المجيب -->
                        <div class="col-md-6 mb-3">
                            <label for="scholar_id" class="form-label">العالم المجيب <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('scholar_id') is-invalid @enderror" 
                                id="scholar_id" 
                                name="scholar_id" 
                                required>
                                <option value="">اختر العالم المجيب</option>
                                @foreach($scholars as $scholar)
                                    <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>
                                        {{ $scholar->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('scholar_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- التصنيف -->
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">التصنيف <span class="text-danger">*</span></label>
                            <select 
                                class="form-select @error('category') is-invalid @enderror" 
                                id="category" 
                                name="category" 
                                required>
                                <option value="">اختر التصنيف</option>
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
                    </div>

                    <!-- الوسوم -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">الوسوم (اختياري)</label>
                        <input type="text" 
                            class="form-control @error('tags') is-invalid @enderror" 
                            id="tags" 
                            name="tags" 
                            value="{{ old('tags') }}"
                            placeholder="أدخل الوسوم مفصولة بفواصل (مثال: صلاة، زكاة، صيام)">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">افصل بين الوسوم بفاصلة (،)</small>
                    </div>

                    <!-- المراجع -->
                    <div class="mb-3">
                        <label for="references" class="form-label">المراجع (اختياري)</label>
                        <textarea 
                            class="form-control @error('references') is-invalid @enderror" 
                            id="references" 
                            name="references" 
                            rows="4"
                            placeholder="أدخل المراجع (كل مرجع في سطر منفصل)">{{ old('references') }}</textarea>
                        @error('references')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">ضع كل مرجع في سطر منفصل</small>
                    </div>

                    <!-- خيارات النشر -->
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title">خيارات النشر</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                    type="checkbox" 
                                    id="is_published" 
                                    name="is_published" 
                                    value="1"
                                    {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    نشر الفتوى مباشرة
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                    type="checkbox" 
                                    id="is_featured" 
                                    name="is_featured" 
                                    value="1"
                                    {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">
                                    فتوى مميزة
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.fatwas') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right"></i>
                            رجوع
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            حفظ الفتوى
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // تفعيل محرر النصوص للإجابة
    ClassicEditor
        .create(document.querySelector('#answer'), {
            language: 'ar',
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endpush

@endsection

