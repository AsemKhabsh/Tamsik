@extends('layouts.app')

@section('title', 'إضافة مقال جديد - تمسيك')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="mb-4">
                <h1 class="page-title mb-2">
                    <i class="fas fa-pen-fancy me-2 text-primary"></i>
                    إضافة مقال جديد
                </h1>
                <p class="text-muted">شارك أفكارك ومقالاتك مع القراء</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>يرجى تصحيح الأخطاء التالية:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Article Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        بيانات المقال
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- العنوان -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                <i class="fas fa-heading text-primary me-1"></i>
                                عنوان المقال <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
                                   placeholder="أدخل عنوان المقال" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- المقدمة/الملخص -->
                        <div class="mb-4">
                            <label for="excerpt" class="form-label fw-bold">
                                <i class="fas fa-align-left text-primary me-1"></i>
                                ملخص المقال
                            </label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" 
                                      name="excerpt" 
                                      rows="3" 
                                      placeholder="ملخص قصير عن المقال (اختياري)">{{ old('excerpt') }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">سيظهر هذا الملخص في قائمة المقالات</small>
                        </div>

                        <!-- المحتوى -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                <i class="fas fa-file-alt text-primary me-1"></i>
                                محتوى المقال <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="15" 
                                      placeholder="اكتب محتوى المقال هنا..." 
                                      required>{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- التصنيف -->
                            <div class="col-md-6 mb-4">
                                <label for="category_id" class="form-label fw-bold">
                                    <i class="fas fa-folder text-primary me-1"></i>
                                    التصنيف
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id">
                                    <option value="">اختر التصنيف (اختياري)</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الصورة المميزة -->
                            <div class="col-md-6 mb-4">
                                <label for="featured_image" class="form-label fw-bold">
                                    <i class="fas fa-image text-primary me-1"></i>
                                    الصورة المميزة
                                </label>
                                <input type="file" 
                                       class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" 
                                       name="featured_image" 
                                       accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">الحد الأقصى: 2 ميجابايت (JPG, PNG, GIF)</small>
                            </div>
                        </div>

                        <!-- الكلمات المفتاحية -->
                        <div class="mb-4">
                            <label for="tags" class="form-label fw-bold">
                                <i class="fas fa-tags text-primary me-1"></i>
                                الكلمات المفتاحية
                            </label>
                            <input type="text" 
                                   class="form-control @error('tags') is-invalid @enderror" 
                                   id="tags" 
                                   name="tags" 
                                   value="{{ old('tags') }}" 
                                   placeholder="مثال: فكر إسلامي، تربية، مجتمع (افصل بينها بفاصلة)">
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">افصل بين الكلمات المفتاحية بفاصلة (,)</small>
                        </div>

                        <!-- SEO Settings (Optional) -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-search text-primary me-1"></i>
                                    إعدادات محركات البحث (اختياري)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">عنوان SEO</label>
                                    <input type="text" 
                                           class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title') }}" 
                                           placeholder="سيتم استخدام عنوان المقال إذا تُرك فارغاً">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="meta_description" class="form-label">وصف SEO</label>
                                    <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                              id="meta_description" 
                                              name="meta_description" 
                                              rows="2" 
                                              placeholder="سيتم استخدام ملخص المقال إذا تُرك فارغاً">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('thinkers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                إلغاء
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    نشر المقال
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fas fa-info-circle me-2"></i>
                        ملاحظات هامة
                    </h6>
                    <ul class="mb-0 small">
                        <li>سيتم مراجعة المقال من قبل الإدارة قبل نشره للجمهور</li>
                        <li>تأكد من كتابة محتوى أصيل وموثوق</li>
                        <li>استخدم لغة واضحة ومفهومة</li>
                        <li>يمكنك تعديل المقال لاحقاً من صفحة "مقالاتي"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/ar.js"></script>
<script>
    let editorInstance;

    // تهيئة CKEditor
    ClassicEditor
        .create(document.querySelector('#content'), {
            language: 'ar',
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'indent', 'outdent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'فقرة', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'عنوان 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'عنوان 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'عنوان 3', class: 'ck-heading_heading3' }
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(editor => {
            editorInstance = editor;
            window.editor = editor;

            // تحديث textarea عند التغيير
            editor.model.document.on('change:data', () => {
                document.querySelector('#content').value = editor.getData();
                updateWordCount();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل المحرر:', error);
        });

    // معاينة الصورة المميزة
    document.getElementById('featured_image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('تم اختيار صورة:', file.name);
            }
            reader.readAsDataURL(file);
        }
    });

    // حساب عدد الكلمات
    function updateWordCount() {
        if (editorInstance) {
            const content = editorInstance.getData();
            const text = content.replace(/<[^>]*>/g, ''); // إزالة HTML tags
            const wordCount = text.trim().split(/\s+/).filter(word => word.length > 0).length;
            const readingTime = Math.max(1, Math.ceil(wordCount / 200));
            console.log(`عدد الكلمات: ${wordCount}, وقت القراءة المقدر: ${readingTime} دقيقة`);
        }
    }
</script>

<style>
    .ck-editor__editable {
        min-height: 400px;
        direction: rtl;
        text-align: right;
    }

    .ck.ck-editor {
        direction: rtl;
    }

    .ck.ck-toolbar {
        direction: ltr;
    }
</style>
@endpush
@endsection

