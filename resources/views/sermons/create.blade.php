@extends('layouts.app')

@section('title', 'إضافة خطبة جديدة - تمسيك')

@push('styles')
<style>
        body {
            font-family: 'Amiri', serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .page-title {
            color: #1d8a4e;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            color: #333;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Amiri', serif;
            transition: border-color 0.3s;
        }
        
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #1d8a4e;
        }
        
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-textarea.large {
            min-height: 300px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Amiri', serif;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1d8a4e, #d4af37);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .back-link {
            color: #1d8a4e;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .help-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .tags-input {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            min-height: 50px;
        }
        
        .tag {
            background: #1d8a4e;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .tag-remove {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 0.8rem;
        }
        
        .tag-input {
            border: none;
            outline: none;
            flex: 1;
            min-width: 100px;
            font-family: 'Amiri', serif;
        }
        
        .char-counter {
            text-align: left;
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="mb-3">
        <a href="{{ route('sermons.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة إلى قائمة الخطب
        </a>
    </div>

    <div class="page-header mb-4">
        <h1 class="page-title">إضافة خطبة جديدة</h1>
        <p class="page-subtitle text-muted">أضف خطبتك بشكل سريع ومباشر</p>
    </div>

    <div class="form-container">
            <form method="POST" action="{{ route('sermons.store') }}" enctype="multipart/form-data">
                @csrf
                
                {{-- معلومات أساسية --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="title" class="form-label">عنوان الخطبة *</label>
                        <input type="text" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}" 
                               class="form-input"
                               placeholder="أدخل عنوان الخطبة"
                               required>
                        <div class="help-text">اختر عنواناً واضحاً ومعبراً عن محتوى الخطبة</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">التصنيف *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">اختر التصنيف</option>
                            <option value="عقيدة" {{ old('category') == 'عقيدة' ? 'selected' : '' }}>العقيدة</option>
                            <option value="عبادات" {{ old('category') == 'عبادات' ? 'selected' : '' }}>العبادات</option>
                            <option value="معاملات" {{ old('category') == 'معاملات' ? 'selected' : '' }}>المعاملات</option>
                            <option value="أخلاق" {{ old('category') == 'أخلاق' ? 'selected' : '' }}>الأخلاق</option>
                            <option value="سيرة" {{ old('category') == 'سيرة' ? 'selected' : '' }}>السيرة النبوية</option>
                            <option value="تفسير" {{ old('category') == 'تفسير' ? 'selected' : '' }}>التفسير</option>
                            <option value="حديث" {{ old('category') == 'حديث' ? 'selected' : '' }}>الحديث الشريف</option>
                            <option value="فقه" {{ old('category') == 'فقه' ? 'selected' : '' }}>الفقه</option>
                            <option value="دعوة" {{ old('category') == 'دعوة' ? 'selected' : '' }}>الدعوة</option>
                            <option value="مناسبات" {{ old('category') == 'مناسبات' ? 'selected' : '' }}>المناسبات</option>
                        </select>
                    </div>
                </div>
                
                {{-- المقدمة --}}
                <div class="form-group">
                    <label for="introduction" class="form-label">مقدمة الخطبة *</label>
                    <textarea id="introduction" 
                              name="introduction" 
                              class="form-textarea"
                              placeholder="اكتب مقدمة مؤثرة للخطبة..."
                              required>{{ old('introduction') }}</textarea>
                    <div class="char-counter" id="intro-counter">0 / 500 حرف</div>
                </div>
                
                {{-- المحتوى الرئيسي --}}
                <div class="form-group">
                    <label for="content" class="form-label">محتوى الخطبة *</label>
                    <textarea id="content" 
                              name="content" 
                              class="form-textarea large"
                              placeholder="اكتب محتوى الخطبة الرئيسي..."
                              required>{{ old('content') }}</textarea>
                    <div class="char-counter" id="content-counter">0 / 5000 حرف</div>
                </div>
                
                {{-- الخاتمة --}}
                <div class="form-group">
                    <label for="conclusion" class="form-label">خاتمة الخطبة *</label>
                    <textarea id="conclusion" 
                              name="conclusion" 
                              class="form-textarea"
                              placeholder="اكتب خاتمة مؤثرة للخطبة..."
                              required>{{ old('conclusion') }}</textarea>
                    <div class="char-counter" id="conclusion-counter">0 / 500 حرف</div>
                </div>
                
                {{-- الكلمات المفتاحية --}}
                <div class="form-group">
                    <label for="tags" class="form-label">الكلمات المفتاحية</label>
                    <div class="tags-input" id="tagsContainer">
                        <input type="text" 
                               id="tagInput" 
                               class="tag-input" 
                               placeholder="أضف كلمة مفتاحية واضغط Enter">
                    </div>
                    <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags') }}">
                    <div class="help-text">أضف كلمات مفتاحية تساعد في البحث عن الخطبة</div>
                </div>
                
                {{-- المراجع --}}
                <div class="form-group">
                    <label for="references" class="form-label">المراجع والمصادر</label>
                    <textarea id="references" 
                              name="references" 
                              class="form-textarea"
                              placeholder="اذكر المراجع والمصادر المستخدمة في الخطبة...">{{ old('references') }}</textarea>
                    <div class="help-text">اذكر الآيات والأحاديث والمراجع المستخدمة</div>
                </div>
                
                {{-- إعدادات النشر --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="is_published" class="form-label">حالة النشر</label>
                        <select id="is_published" name="is_published" class="form-select">
                            <option value="0" {{ old('is_published') == '0' ? 'selected' : '' }}>مسودة</option>
                            <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>منشورة</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="audio_file" class="form-label">ملف صوتي (اختياري)</label>
                        <input type="file" 
                               id="audio_file" 
                               name="audio_file" 
                               class="form-input"
                               accept="audio/*">
                        <div class="help-text">يمكنك رفع تسجيل صوتي للخطبة</div>
                    </div>
                </div>
                
                {{-- أزرار الإجراءات --}}
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ الخطبة
                    </button>
                    <a href="{{ route('sermons.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
</div>

@push('scripts')
<script>
        // عداد الأحرف
        function setupCharCounter(textareaId, counterId, maxLength) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);
            
            textarea.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = `${length} / ${maxLength} حرف`;
                
                if (length > maxLength * 0.9) {
                    counter.style.color = '#dc3545';
                } else if (length > maxLength * 0.7) {
                    counter.style.color = '#ffc107';
                } else {
                    counter.style.color = '#666';
                }
            });
        }
        
        setupCharCounter('introduction', 'intro-counter', 500);
        setupCharCounter('content', 'content-counter', 5000);
        setupCharCounter('conclusion', 'conclusion-counter', 500);
        
        // نظام الكلمات المفتاحية
        const tagsContainer = document.getElementById('tagsContainer');
        const tagInput = document.getElementById('tagInput');
        const tagsHidden = document.getElementById('tagsHidden');
        let tags = [];
        
        // تحميل الكلمات المفتاحية المحفوظة
        if (tagsHidden.value) {
            tags = JSON.parse(tagsHidden.value);
            renderTags();
        }
        
        tagInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag();
            }
        });
        
        function addTag() {
            const tag = tagInput.value.trim();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                tagInput.value = '';
                renderTags();
                updateHiddenInput();
            }
        }
        
        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
            updateHiddenInput();
        }
        
        function renderTags() {
            const tagElements = tags.map((tag, index) => 
                `<span class="tag">
                    ${tag}
                    <button type="button" class="tag-remove" onclick="removeTag(${index})">×</button>
                </span>`
            ).join('');
            
            tagsContainer.innerHTML = tagElements + 
                '<input type="text" id="tagInput" class="tag-input" placeholder="أضف كلمة مفتاحية واضغط Enter">';
            
            // إعادة ربط الأحداث
            const newTagInput = document.getElementById('tagInput');
            newTagInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addTag();
                }
            });
        }
        
        function updateHiddenInput() {
            tagsHidden.value = JSON.stringify(tags);
        }
    </script>

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
        };

        // تهيئة المحررات
        let introductionEditor, contentEditor, conclusionEditor, referencesEditor;

        // محرر المقدمة
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

        // محرر المحتوى الرئيسي
        ClassicEditor
            .create(document.querySelector('#content'), editorConfig)
            .then(editor => {
                contentEditor = editor;
                editor.model.document.on('change:data', () => {
                    document.querySelector('#content').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('خطأ في تحميل محرر المحتوى:', error);
            });

        // محرر الخاتمة
        ClassicEditor
            .create(document.querySelector('#conclusion'), editorConfig)
            .then(editor => {
                conclusionEditor = editor;
                editor.model.document.on('change:data', () => {
                    document.querySelector('#conclusion').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('خطأ في تحميل محرر الخاتمة:', error);
            });

        // محرر المراجع
        ClassicEditor
            .create(document.querySelector('#references'), editorConfig)
            .then(editor => {
                referencesEditor = editor;
                editor.model.document.on('change:data', () => {
                    document.querySelector('#references').value = editor.getData();
                });
            })
            .catch(error => {
                console.error('خطأ في تحميل محرر المراجع:', error);
            });
    </script>

    <style>
        .ck-editor__editable {
            min-height: 200px;
            direction: rtl;
            text-align: right;
        }

        .ck.ck-editor {
            direction: rtl;
        }

        .ck.ck-toolbar {
            direction: ltr;
        }

        #content + .ck-editor .ck-editor__editable {
            min-height: 400px;
        }
    </style>
@endpush

@endsection
