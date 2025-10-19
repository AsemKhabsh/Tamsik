@extends('admin.layout')

@section('title', 'تعديل الخطبة')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-book-open"></i>
                        تعديل الخطبة: {{ $sermon->title }}
                    </h4>
                    <a href="{{ route('admin.sermons') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sermons.update', $sermon) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- عنوان الخطبة -->
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="title" class="form-label">عنوان الخطبة *</label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                           value="{{ old('title', $sermon->title) }}" required>
                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- التصنيف -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-label">التصنيف *</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="aqeedah" {{ old('category', $sermon->category) == 'aqeedah' ? 'selected' : '' }}>العقيدة</option>
                                        <option value="fiqh" {{ old('category', $sermon->category) == 'fiqh' ? 'selected' : '' }}>الفقه</option>
                                        <option value="tafseer" {{ old('category', $sermon->category) == 'tafseer' ? 'selected' : '' }}>التفسير</option>
                                        <option value="hadith" {{ old('category', $sermon->category) == 'hadith' ? 'selected' : '' }}>الحديث</option>
                                        <option value="seerah" {{ old('category', $sermon->category) == 'seerah' ? 'selected' : '' }}>السيرة</option>
                                        <option value="akhlaq" {{ old('category', $sermon->category) == 'akhlaq' ? 'selected' : '' }}>الأخلاق</option>
                                        <option value="other" {{ old('category', $sermon->category) == 'other' ? 'selected' : '' }}>أخرى</option>
                                    </select>
                                    @error('category')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- المؤلف -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="author" class="form-label">المؤلف *</label>
                                    <input type="text" class="form-control" id="author" name="author" 
                                           value="{{ old('author', $sermon->author) }}" required>
                                    @error('author')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- تاريخ الخطبة -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sermon_date" class="form-label">تاريخ الخطبة</label>
                                    <input type="date" class="form-control" id="sermon_date" name="sermon_date" 
                                           value="{{ old('sermon_date', $sermon->sermon_date ? $sermon->sermon_date->format('Y-m-d') : '') }}">
                                    @error('sermon_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- المقدمة -->
                        <div class="form-group mb-3">
                            <label for="introduction" class="form-label">مقدمة الخطبة</label>
                            <textarea class="form-control" id="introduction" name="introduction" rows="4" 
                                      placeholder="مقدمة الخطبة...">{{ old('introduction', $sermon->introduction) }}</textarea>
                            <small class="text-muted">عدد الأحرف: <span id="introCount">{{ strlen($sermon->introduction ?? '') }}</span>/500</small>
                            @error('introduction')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- محتوى الخطبة -->
                        <div class="form-group mb-3">
                            <label for="content" class="form-label">محتوى الخطبة *</label>
                            <textarea class="form-control" id="content" name="content" rows="10" 
                                      placeholder="محتوى الخطبة الرئيسي..." required>{{ old('content', $sermon->content) }}</textarea>
                            <small class="text-muted">عدد الأحرف: <span id="contentCount">{{ strlen($sermon->content ?? '') }}</span>/5000</small>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الخاتمة -->
                        <div class="form-group mb-3">
                            <label for="conclusion" class="form-label">خاتمة الخطبة</label>
                            <textarea class="form-control" id="conclusion" name="conclusion" rows="4" 
                                      placeholder="خاتمة الخطبة...">{{ old('conclusion', $sermon->conclusion) }}</textarea>
                            <small class="text-muted">عدد الأحرف: <span id="conclusionCount">{{ strlen($sermon->conclusion ?? '') }}</span>/500</small>
                            @error('conclusion')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- الملف الصوتي -->
                        <div class="form-group mb-3">
                            <label for="audio_file" class="form-label">الملف الصوتي</label>
                            <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/*">
                            @if($sermon->audio_file)
                                <div class="mt-2">
                                    <audio controls class="w-100">
                                        <source src="{{ asset('storage/' . $sermon->audio_file) }}" type="audio/mpeg">
                                        متصفحك لا يدعم تشغيل الملفات الصوتية.
                                    </audio>
                                    <p class="text-muted mt-1">الملف الصوتي الحالي</p>
                                </div>
                            @endif
                            @error('audio_file')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- خيارات النشر -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_published" name="is_published" 
                                               value="1" {{ old('is_published', $sermon->is_published) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            نشر الخطبة
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" 
                                               value="1" {{ old('is_featured', $sermon->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            خطبة مميزة
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i>
                                حفظ التغييرات
                            </button>
                            <button type="submit" name="save_as_draft" value="1" class="btn btn-warning">
                                <i class="fas fa-file-alt"></i>
                                حفظ كمسودة
                            </button>
                            <a href="{{ route('admin.sermons') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// عدادات الأحرف
function setupCharCounter(textareaId, counterId, maxLength) {
    const textarea = document.getElementById(textareaId);
    const counter = document.getElementById(counterId);
    
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        counter.textContent = count;
        
        if (count > maxLength) {
            this.style.borderColor = '#dc3545';
            counter.style.color = '#dc3545';
        } else {
            this.style.borderColor = '#ced4da';
            counter.style.color = '#6c757d';
        }
    });
}

// تطبيق العدادات
setupCharCounter('introduction', 'introCount', 500);
setupCharCounter('content', 'contentCount', 5000);
setupCharCounter('conclusion', 'conclusionCount', 500);

// حفظ كمسودة
document.querySelector('button[name="save_as_draft"]').addEventListener('click', function() {
    document.getElementById('is_published').checked = false;
});
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
    let introductionEditor, contentEditor, conclusionEditor;

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

    // محرر المحتوى
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
</script>

<style>
    .ck-editor__editable {
        min-height: 150px;
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
@endsection
