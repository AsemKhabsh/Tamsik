@extends('layouts.app')

@section('title', 'إعداد خطبة جديدة - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #1d8a4e 0%, #2c5530 100%);">
                <div class="card-body text-center py-4">
                    <h1 class="text-white mb-2">
                        <i class="fas fa-pen-fancy me-2"></i>
                        إعداد خطبة جديدة
                    </h1>
                    <p class="text-white-50 fs-6 mb-0">أداة مساعدة لتنظيم وإعداد خطبتك بطريقة احترافية</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sermon Form -->
    <form action="{{ route('sermon.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1️⃣ المعلومات الأساسية -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    المعلومات الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- العنوان الرئيسي -->
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label fw-semibold">
                            <i class="fas fa-heading me-1 text-primary"></i>
                            العنوان الرئيسي <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="title"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="أدخل العنوان الرئيسي للخطبة"
                               value="{{ old('title') }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <button type="button" class="btn btn-sm btn-outline-info mt-2">
                            <i class="fas fa-lightbulb me-1"></i>
                            اقتراحات
                        </button>
                    </div>

                    <!-- تاريخ الخطبة -->
                    <div class="col-md-6 mb-3">
                        <label for="sermon_date" class="form-label fw-semibold">
                            <i class="fas fa-calendar me-1 text-primary"></i>
                            تاريخ الخطبة
                        </label>
                        <input type="date"
                               id="sermon_date"
                               name="sermon_date"
                               class="form-control"
                               value="{{ old('sermon_date', date('Y-m-d')) }}">
                    </div>

                    <!-- المناسبة -->
                    <div class="col-md-6 mb-3">
                        <label for="occasion" class="form-label fw-semibold">
                            <i class="fas fa-star me-1 text-primary"></i>
                            المناسبة
                        </label>
                        <select id="occasion" name="occasion" class="form-select">
                            <option value="">اختر المناسبة</option>
                            <option value="جمعة">خطبة جمعة</option>
                            <option value="عيد_فطر">عيد الفطر</option>
                            <option value="عيد_اضحى">عيد الأضحى</option>
                            <option value="رمضان">رمضان</option>
                            <option value="حج">الحج</option>
                            <option value="محرم">محرم</option>
                            <option value="مولد">المولد النبوي</option>
                            <option value="اخرى">مناسبة أخرى</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2️⃣ المقدمة -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book-open me-2"></i>
                    📖 المقدمة
                </h5>
            </div>
            <div class="card-body">
                <!-- الموضوع -->
                <div class="mb-3">
                    <label for="intro_topic" class="form-label fw-semibold">
                        <i class="fas fa-lightbulb me-1 text-warning"></i>
                        الموضوع
                    </label>
                    <textarea id="intro_topic"
                              name="intro_topic"
                              class="form-control"
                              rows="2"
                              placeholder="حدد موضوع المقدمة...">{{ old('intro_topic') }}</textarea>
                </div>

                <!-- الشاهد -->
                <div class="mb-3">
                    <label for="intro_evidence" class="form-label fw-semibold">
                        <i class="fas fa-quote-right me-1 text-info"></i>
                        الشاهد
                    </label>
                    <textarea id="intro_evidence"
                              name="intro_evidence"
                              class="form-control"
                              rows="2"
                              placeholder="آية أو حديث أو قول...">{{ old('intro_evidence') }}</textarea>
                </div>

                <!-- الفكرة -->
                <div class="mb-3">
                    <label for="intro_idea" class="form-label fw-semibold">
                        <i class="fas fa-brain me-1 text-primary"></i>
                        الفكرة
                    </label>
                    <textarea id="intro_idea"
                              name="intro_idea"
                              class="form-control"
                              rows="2"
                              placeholder="الفكرة الرئيسية للمقدمة...">{{ old('intro_idea') }}</textarea>
                </div>

                <!-- قصة (اختياري) -->
                <div class="mb-3">
                    <label for="intro_story" class="form-label fw-semibold">
                        <i class="fas fa-book-reader me-1 text-secondary"></i>
                        قصة (اختياري)
                    </label>
                    <textarea id="intro_story"
                              name="intro_story"
                              class="form-control"
                              rows="3"
                              placeholder="قصة توضيحية أو مثال...">{{ old('intro_story') }}</textarea>
                </div>

                <!-- الربط -->
                <div class="mb-3">
                    <label for="intro_connection" class="form-label fw-semibold">
                        <i class="fas fa-link me-1 text-success"></i>
                        الربط
                    </label>
                    <textarea id="intro_connection"
                              name="intro_connection"
                              class="form-control"
                              rows="2"
                              placeholder="ربط المقدمة بالموضوع الرئيسي...">{{ old('intro_connection') }}</textarea>
                </div>

                <!-- نص المقدمة الكامل -->
                <div class="mb-0">
                    <label for="introduction" class="form-label fw-semibold">
                        <i class="fas fa-paragraph me-1 text-primary"></i>
                        نص المقدمة الكامل <span class="text-danger">*</span>
                    </label>
                    <textarea id="introduction"
                              name="introduction"
                              class="form-control"
                              rows="6"
                              placeholder="اكتب نص المقدمة كاملاً..."
                              required>{{ old('introduction') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 3️⃣ الخطبة الأولى -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-mosque me-2"></i>
                    📢 الخطبة الأولى
                </h5>
            </div>
            <div class="card-body">
                <!-- العنصر الأول -->
                <div class="mb-3">
                    <label for="first_sermon_element1" class="form-label fw-semibold">
                        <i class="fas fa-1 me-1 text-primary"></i>
                        العنصر الأول
                    </label>
                    <input type="text"
                           id="first_sermon_element1"
                           name="first_sermon_element1"
                           class="form-control mb-2"
                           placeholder="عنوان العنصر الأول">
                    <textarea name="first_sermon_element1_content"
                              class="form-control"
                              rows="4"
                              placeholder="محتوى العنصر الأول...">{{ old('first_sermon_element1_content') }}</textarea>
                </div>

                <!-- العنصر الثاني -->
                <div class="mb-3">
                    <label for="first_sermon_element2" class="form-label fw-semibold">
                        <i class="fas fa-2 me-1 text-primary"></i>
                        العنصر الثاني
                    </label>
                    <input type="text"
                           id="first_sermon_element2"
                           name="first_sermon_element2"
                           class="form-control mb-2"
                           placeholder="عنوان العنصر الثاني">
                    <textarea name="first_sermon_element2_content"
                              class="form-control"
                              rows="4"
                              placeholder="محتوى العنصر الثاني...">{{ old('first_sermon_element2_content') }}</textarea>
                </div>

                <!-- العنصر الثالث -->
                <div class="mb-3">
                    <label for="first_sermon_element3" class="form-label fw-semibold">
                        <i class="fas fa-3 me-1 text-primary"></i>
                        العنصر الثالث
                    </label>
                    <input type="text"
                           id="first_sermon_element3"
                           name="first_sermon_element3"
                           class="form-control mb-2"
                           placeholder="عنوان العنصر الثالث">
                    <textarea name="first_sermon_element3_content"
                              class="form-control"
                              rows="4"
                              placeholder="محتوى العنصر الثالث...">{{ old('first_sermon_element3_content') }}</textarea>
                </div>

                <!-- نص الخطبة الأولى الكامل -->
                <div class="mb-0">
                    <label for="main_content" class="form-label fw-semibold">
                        <i class="fas fa-align-left me-1 text-primary"></i>
                        نص الخطبة الأولى الكامل <span class="text-danger">*</span>
                    </label>
                    <textarea id="main_content"
                              name="main_content"
                              class="form-control"
                              rows="8"
                              placeholder="اكتب نص الخطبة الأولى كاملاً..."
                              required>{{ old('main_content') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 4️⃣ الخطبة الثانية -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="fas fa-mosque me-2"></i>
                    📢 الخطبة الثانية
                </h5>
            </div>
            <div class="card-body">
                <!-- العنصر الأول -->
                <div class="mb-3">
                    <label for="second_sermon_element1" class="form-label fw-semibold">
                        <i class="fas fa-1 me-1 text-primary"></i>
                        العنصر الأول
                    </label>
                    <input type="text"
                           id="second_sermon_element1"
                           name="second_sermon_element1"
                           class="form-control mb-2"
                           placeholder="عنوان العنصر الأول">
                    <textarea name="second_sermon_element1_content"
                              class="form-control"
                              rows="4"
                              placeholder="محتوى العنصر الأول...">{{ old('second_sermon_element1_content') }}</textarea>
                </div>

                <!-- العنصر الثاني -->
                <div class="mb-3">
                    <label for="second_sermon_element2" class="form-label fw-semibold">
                        <i class="fas fa-2 me-1 text-primary"></i>
                        العنصر الثاني
                    </label>
                    <input type="text"
                           id="second_sermon_element2"
                           name="second_sermon_element2"
                           class="form-control mb-2"
                           placeholder="عنوان العنصر الثاني">
                    <textarea name="second_sermon_element2_content"
                              class="form-control"
                              rows="4"
                              placeholder="محتوى العنصر الثاني...">{{ old('second_sermon_element2_content') }}</textarea>
                </div>

                <!-- نص الخطبة الثانية الكامل -->
                <div class="mb-0">
                    <label for="conclusion" class="form-label fw-semibold">
                        <i class="fas fa-align-left me-1 text-primary"></i>
                        نص الخطبة الثانية الكامل <span class="text-danger">*</span>
                    </label>
                    <textarea id="conclusion"
                              name="conclusion"
                              class="form-control"
                              rows="8"
                              placeholder="اكتب نص الخطبة الثانية كاملاً..."
                              required>{{ old('conclusion') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5️⃣ المراجع والمصادر -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>
                    📚 المراجع والمصادر
                </h5>
            </div>
            <div class="card-body">
                <!-- الآيات القرآنية -->
                <div class="mb-3">
                    <label for="quran_verses" class="form-label fw-semibold">
                        <i class="fas fa-quran me-1 text-success"></i>
                        الآيات القرآنية
                    </label>
                    <textarea id="quran_verses"
                              name="quran_verses"
                              class="form-control"
                              rows="3"
                              placeholder="اذكر الآيات القرآنية المستخدمة مع السورة ورقم الآية...">{{ old('quran_verses') }}</textarea>
                </div>

                <!-- الأحاديث النبوية -->
                <div class="mb-3">
                    <label for="hadiths" class="form-label fw-semibold">
                        <i class="fas fa-book-open me-1 text-info"></i>
                        الأحاديث النبوية
                    </label>
                    <textarea id="hadiths"
                              name="hadiths"
                              class="form-control"
                              rows="3"
                              placeholder="اذكر الأحاديث النبوية المستخدمة مع المصدر...">{{ old('hadiths') }}</textarea>
                </div>

                <!-- أقوال العلماء -->
                <div class="mb-3">
                    <label for="scholars_quotes" class="form-label fw-semibold">
                        <i class="fas fa-user-graduate me-1 text-primary"></i>
                        أقوال العلماء
                    </label>
                    <textarea id="scholars_quotes"
                              name="scholars_quotes"
                              class="form-control"
                              rows="3"
                              placeholder="اذكر أقوال العلماء المستخدمة...">{{ old('scholars_quotes') }}</textarea>
                </div>

                <!-- المراجع الأخرى -->
                <div class="mb-0">
                    <label for="references" class="form-label fw-semibold">
                        <i class="fas fa-bookmark me-1 text-warning"></i>
                        المراجع الأخرى
                    </label>
                    <textarea id="references"
                              name="references"
                              class="form-control"
                              rows="3"
                              placeholder="اذكر المراجع والكتب الأخرى المستخدمة...">{{ old('references') }}</textarea>
                </div>
            </div>
        </div>

        <!-- 6️⃣ التفاصيل الإضافية -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>
                    ⚙️ التفاصيل الإضافية
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- الأهداف -->
                    <div class="col-md-6 mb-3">
                        <label for="objectives" class="form-label fw-semibold">
                            <i class="fas fa-bullseye me-1 text-danger"></i>
                            الأهداف
                        </label>
                        <textarea id="objectives"
                                  name="objectives"
                                  class="form-control"
                                  rows="3"
                                  placeholder="ما الأهداف المرجوة من هذه الخطبة؟">{{ old('objectives') }}</textarea>
                    </div>

                    <!-- الفئة المستهدفة -->
                    <div class="col-md-6 mb-3">
                        <label for="target_audience" class="form-label fw-semibold">
                            <i class="fas fa-users me-1 text-primary"></i>
                            الفئة المستهدفة
                        </label>
                        <select id="target_audience" name="target_audience" class="form-select">
                            <option value="">اختر الفئة المستهدفة</option>
                            <option value="عامة">عامة الناس</option>
                            <option value="شباب">الشباب</option>
                            <option value="نساء">النساء</option>
                            <option value="اطفال">الأطفال</option>
                            <option value="طلاب_علم">طلاب العلم</option>
                            <option value="متخصصة">فئة متخصصة</option>
                        </select>
                    </div>

                    <!-- مدة الخطبة -->
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label fw-semibold">
                            <i class="fas fa-clock me-1 text-info"></i>
                            مدة الخطبة (بالدقائق)
                        </label>
                        <input type="number"
                               id="duration"
                               name="duration"
                               class="form-control"
                               placeholder="مثال: 30"
                               min="5"
                               max="120">
                    </div>

                    <!-- ملاحظات -->
                    <div class="col-md-12 mb-0">
                        <label for="notes" class="form-label fw-semibold">
                            <i class="fas fa-sticky-note me-1 text-warning"></i>
                            ملاحظات
                        </label>
                        <textarea id="notes"
                                  name="notes"
                                  class="form-control"
                                  rows="3"
                                  placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>
                        حفظ الخطبة
                    </button>
                    <a href="{{ route('sermons.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        إلغاء
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.card-header {
    font-weight: 600;
}

.form-label {
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.card {
    border: none;
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}
</style>

@push('scripts')
<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/ar.js"></script>
<script>
    // إعدادات CKEditor المشتركة
    const editorConfig = {
        language: 'ar',
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'link', 'bulletedList', 'numberedList', '|',
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
    };

    // تهيئة المحررات للحقول الرئيسية
    let introductionEditor, mainContentEditor, conclusionEditor;

    // محرر نص المقدمة الكامل
    ClassicEditor
        .create(document.querySelector('#introduction'), editorConfig)
        .then(editor => {
            introductionEditor = editor;
            editor.model.document.on('change:data', () => {
                document.querySelector('#introduction').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل محرر المقدمة:', error);
        });

    // محرر نص الخطبة الأولى الكامل
    ClassicEditor
        .create(document.querySelector('#main_content'), editorConfig)
        .then(editor => {
            mainContentEditor = editor;
            editor.model.document.on('change:data', () => {
                document.querySelector('#main_content').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل محرر المحتوى الرئيسي:', error);
        });

    // محرر نص الخطبة الثانية الكامل
    ClassicEditor
        .create(document.querySelector('#conclusion'), editorConfig)
        .then(editor => {
            conclusionEditor = editor;
            editor.model.document.on('change:data', () => {
                document.querySelector('#conclusion').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل محرر الخطبة الثانية:', error);
        });
</script>

<style>
    .ck-editor__editable {
        min-height: 250px;
        direction: rtl;
        text-align: right;
    }

    .ck.ck-editor {
        direction: rtl;
    }

    .ck.ck-toolbar {
        direction: ltr;
    }

    #introduction + .ck-editor .ck-editor__editable {
        min-height: 300px;
    }

    #main_content + .ck-editor .ck-editor__editable {
        min-height: 400px;
    }

    #conclusion + .ck-editor .ck-editor__editable {
        min-height: 400px;
    }
</style>
@endpush
@endsection