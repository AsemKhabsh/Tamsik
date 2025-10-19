<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة محاضرة جديدة - تمسك</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <!-- الخطوط -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            width: 20px;
            height: 20px;
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
</head>
<body>
    <div class="container">
        <a href="{{ route('lectures.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            العودة إلى قائمة المحاضرات
        </a>

        <div class="page-header">
            <h1 class="page-title">إضافة محاضرة جديدة</h1>
            <p class="page-subtitle">املأ البيانات التالية لإضافة محاضرة جديدة</p>
        </div>

        <div class="form-container">
            <form method="POST" action="{{ route('lectures.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- معلومات أساسية --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="title" class="form-label">عنوان المحاضرة *</label>
                        <input type="text"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               class="form-input"
                               placeholder="أدخل عنوان المحاضرة"
                               required>
                        <div class="help-text">اختر عنواناً واضحاً ومعبراً عن محتوى المحاضرة</div>
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">التصنيف *</label>
                        <select id="category" name="category" class="form-select" required>
                            <option value="">اختر التصنيف</option>
                            <option value="عقيدة" {{ old('category') == 'عقيدة' ? 'selected' : '' }}>العقيدة</option>
                            <option value="فقه" {{ old('category') == 'فقه' ? 'selected' : '' }}>الفقه</option>
                            <option value="تفسير" {{ old('category') == 'تفسير' ? 'selected' : '' }}>التفسير</option>
                            <option value="حديث" {{ old('category') == 'حديث' ? 'selected' : '' }}>الحديث الشريف</option>
                            <option value="سيرة" {{ old('category') == 'سيرة' ? 'selected' : '' }}>السيرة النبوية</option>
                            <option value="أخلاق" {{ old('category') == 'أخلاق' ? 'selected' : '' }}>الأخلاق</option>
                            <option value="دعوة" {{ old('category') == 'دعوة' ? 'selected' : '' }}>الدعوة</option>
                            <option value="تربية" {{ old('category') == 'تربية' ? 'selected' : '' }}>التربية</option>
                            <option value="أخرى" {{ old('category') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                    </div>
                </div>

                {{-- الوصف --}}
                <div class="form-group">
                    <label for="description" class="form-label">وصف المحاضرة *</label>
                    <textarea id="description"
                              name="description"
                              class="form-textarea"
                              placeholder="اكتب وصفاً تفصيلياً للمحاضرة..."
                              required>{{ old('description') }}</textarea>
                    <div class="help-text">صف محتوى المحاضرة والمواضيع التي ستتناولها</div>
                </div>

                {{-- التوقيت والمدة --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="scheduled_at" class="form-label">تاريخ ووقت المحاضرة *</label>
                        <input type="datetime-local"
                               id="scheduled_at"
                               name="scheduled_at"
                               value="{{ old('scheduled_at') }}"
                               class="form-input"
                               required>
                        <div class="help-text">حدد موعد انعقاد المحاضرة</div>
                    </div>

                    <div class="form-group">
                        <label for="duration" class="form-label">مدة المحاضرة (بالدقائق) *</label>
                        <select id="duration" name="duration" class="form-select" required>
                            <option value="">اختر المدة</option>
                            <option value="30" {{ old('duration') == '30' ? 'selected' : '' }}>30 دقيقة</option>
                            <option value="45" {{ old('duration') == '45' ? 'selected' : '' }}>45 دقيقة</option>
                            <option value="60" {{ old('duration') == '60' ? 'selected' : '' }}>ساعة واحدة</option>
                            <option value="90" {{ old('duration') == '90' ? 'selected' : '' }}>ساعة ونصف</option>
                            <option value="120" {{ old('duration') == '120' ? 'selected' : '' }}>ساعتان</option>
                            <option value="180" {{ old('duration') == '180' ? 'selected' : '' }}>3 ساعات</option>
                        </select>
                    </div>
                </div>

                {{-- المكان والرابط --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="location" class="form-label">المكان (للمحاضرات الحضورية)</label>
                        <input type="text"
                               id="location"
                               name="location"
                               value="{{ old('location') }}"
                               class="form-input"
                               placeholder="مثال: مسجد النور - صنعاء">
                        <div class="help-text">حدد مكان انعقاد المحاضرة إن كانت حضورية</div>
                    </div>

                    <div class="form-group">
                        <label for="online_link" class="form-label">رابط البث المباشر</label>
                        <input type="url"
                               id="online_link"
                               name="online_link"
                               value="{{ old('online_link') }}"
                               class="form-input"
                               placeholder="https://zoom.us/j/...">
                        <div class="help-text">أضف رابط البث إن كانت المحاضرة عبر الإنترنت</div>
                    </div>
                </div>

                {{-- معلومات إضافية --}}
                <div class="form-row">
                    <div class="form-group">
                        <label for="target_audience" class="form-label">الجمهور المستهدف</label>
                        <input type="text"
                               id="target_audience"
                               name="target_audience"
                               value="{{ old('target_audience') }}"
                               class="form-input"
                               placeholder="مثال: طلاب العلم، عموم المسلمين">
                        <div class="help-text">حدد الفئة المستهدفة من المحاضرة</div>
                    </div>

                    <div class="form-group">
                        <label for="max_attendees" class="form-label">الحد الأقصى للحضور</label>
                        <input type="number"
                               id="max_attendees"
                               name="max_attendees"
                               value="{{ old('max_attendees') }}"
                               class="form-input"
                               min="1"
                               placeholder="اتركه فارغاً إذا لم يكن هناك حد">
                        <div class="help-text">حدد العدد الأقصى للحضور إن وجد</div>
                    </div>
                </div>

                {{-- المتطلبات المسبقة --}}
                <div class="form-group">
                    <label for="prerequisites" class="form-label">المتطلبات المسبقة</label>
                    <textarea id="prerequisites"
                              name="prerequisites"
                              class="form-textarea"
                              placeholder="ما يجب على الحضور معرفته مسبقاً...">{{ old('prerequisites') }}</textarea>
                    <div class="help-text">اذكر أي معرفة مسبقة مطلوبة للاستفادة من المحاضرة</div>
                </div>

                {{-- التسعير --}}
                <div class="form-group">
                    <label class="form-label">نوع المحاضرة *</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio"
                                   name="is_free"
                                   id="is_free_yes"
                                   value="1"
                                   {{ old('is_free', '1') == '1' ? 'checked' : '' }}>
                            <label for="is_free_yes">مجانية</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio"
                                   name="is_free"
                                   id="is_free_no"
                                   value="0"
                                   {{ old('is_free') == '0' ? 'checked' : '' }}>
                            <label for="is_free_no">مدفوعة</label>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="price_field" style="display: none;">
                    <label for="price" class="form-label">السعر (ريال يمني)</label>
                    <input type="number"
                           id="price"
                           name="price"
                           value="{{ old('price') }}"
                           class="form-input"
                           min="0"
                           step="0.01"
                           placeholder="أدخل السعر">
                    <div class="help-text">حدد سعر الحضور للمحاضرة</div>
                </div>

                {{-- الصورة المميزة --}}
                <div class="form-group">
                    <label for="featured_image" class="form-label">صورة المحاضرة</label>
                    <input type="file"
                           id="featured_image"
                           name="featured_image"
                           class="form-input"
                           accept="image/*">
                    <div class="help-text">الحد الأقصى: 2 ميجابايت. الصيغ المدعومة: JPG, PNG, GIF</div>
                </div>

                {{-- أزرار التحكم --}}
                <div class="form-actions">
                    <a href="{{ route('lectures.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ المحاضرة
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isFreeRadios = document.querySelectorAll('input[name="is_free"]');
    const priceField = document.getElementById('price_field');
    
    function togglePriceField() {
        const isFree = document.querySelector('input[name="is_free"]:checked').value;
        if (isFree === '0') {
            priceField.style.display = 'block';
            document.getElementById('price').required = true;
        } else {
            priceField.style.display = 'none';
            document.getElementById('price').required = false;
        }
    }

    isFreeRadios.forEach(radio => {
        radio.addEventListener('change', togglePriceField);
    });

    // تشغيل عند تحميل الصفحة
    togglePriceField();
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
    let descriptionEditor, prerequisitesEditor;

    // محرر الوصف
    ClassicEditor
        .create(document.querySelector('#description'), editorConfig)
        .then(editor => {
            descriptionEditor = editor;
            editor.model.document.on('change:data', () => {
                document.querySelector('#description').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل محرر الوصف:', error);
        });

    // محرر المتطلبات المسبقة
    ClassicEditor
        .create(document.querySelector('#prerequisites'), editorConfig)
        .then(editor => {
            prerequisitesEditor = editor;
            editor.model.document.on('change:data', () => {
                document.querySelector('#prerequisites').value = editor.getData();
            });
        })
        .catch(error => {
            console.error('خطأ في تحميل محرر المتطلبات:', error);
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

    #description + .ck-editor .ck-editor__editable {
        min-height: 300px;
    }
</style>
</body>
</html>