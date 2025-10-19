# 🧩 دليل المكونات الموحدة - منصة تمسيك

## 📋 نظرة عامة

هذا الدليل يوضح كيفية استخدام المكونات الموحدة (Modals) والفئات المساعدة في منصة تمسيك.

---

## 🔔 النماذج المنبثقة (Modals)

تم إضافة مجموعة من النماذج المنبثقة الموحدة في ملف `resources/views/components/modals.blade.php` والتي يتم تضمينها تلقائياً في جميع الصفحات.

### 1️⃣ Modal الحذف (Delete Confirmation)

**الاستخدام:**

```javascript
// عرض modal الحذف
showDeleteModal('هل تريد حذف هذه الخطبة؟', function() {
    // الكود الذي يتم تنفيذه عند التأكيد
    fetch('/sermons/delete/123', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        showSuccessModal('تم الحذف بنجاح!');
        location.reload();
    })
    .catch(error => {
        showErrorModal('حدث خطأ أثناء الحذف');
    });
});
```

**مثال في HTML:**

```html
<button class="btn btn-danger" onclick="showDeleteModal('هل تريد حذف هذا العنصر؟', function() { 
    document.getElementById('delete-form-123').submit(); 
})">
    <i class="fas fa-trash me-2"></i>
    حذف
</button>

<form id="delete-form-123" action="{{ route('sermons.destroy', $sermon->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
```

---

### 2️⃣ Modal التأكيد (Confirmation)

**الاستخدام:**

```javascript
// عرض modal التأكيد
showConfirmModal(
    'نشر الخطبة',  // العنوان
    'هل تريد نشر هذه الخطبة للجمهور؟',  // الرسالة
    function() {
        // الكود الذي يتم تنفيذه عند التأكيد
        document.getElementById('publish-form').submit();
    }
);
```

**مثال في HTML:**

```html
<button class="btn btn-primary" onclick="showConfirmModal('نشر الخطبة', 'هل تريد نشر هذه الخطبة؟', function() {
    document.getElementById('publish-form').submit();
})">
    <i class="fas fa-check me-2"></i>
    نشر
</button>
```

---

### 3️⃣ Modal النجاح (Success)

**الاستخدام:**

```javascript
// عرض modal النجاح
showSuccessModal('تم حفظ الخطبة بنجاح!');
```

**مثال في Blade:**

```php
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessModal('{{ session('success') }}');
        });
    </script>
@endif
```

---

### 4️⃣ Modal الخطأ (Error)

**الاستخدام:**

```javascript
// عرض modal الخطأ
showErrorModal('حدث خطأ أثناء حفظ البيانات');
```

**مثال في Blade:**

```php
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showErrorModal('{{ session('error') }}');
        });
    </script>
@endif
```

---

### 5️⃣ Modal التحذير (Warning)

**الاستخدام:**

```javascript
// عرض modal التحذير
showWarningModal('يرجى ملء جميع الحقول المطلوبة');
```

---

### 6️⃣ Modal المعلومات (Info)

**الاستخدام:**

```javascript
// عرض modal المعلومات
showInfoModal('معلومة مهمة', 'يجب أن تكون الخطبة باللغة العربية الفصحى');
```

---

## 📱 الفئات المساعدة للموبايل

تم إضافة مجموعة من الفئات المساعدة لتحسين عرض الصفحات على الأجهزة المحمولة.

### إخفاء/إظهار على الموبايل

```html
<!-- إخفاء على الموبايل -->
<div class="hide-mobile">
    هذا المحتوى لن يظهر على الموبايل
</div>

<!-- إظهار فقط على الموبايل -->
<div class="show-mobile" style="display: none;">
    هذا المحتوى يظهر فقط على الموبايل
</div>
```

### عرض كامل على الموبايل

```html
<!-- عرض كامل على الموبايل -->
<button class="btn btn-primary w-mobile-100">
    زر بعرض كامل على الموبايل
</button>
```

### محاذاة في المنتصف على الموبايل

```html
<!-- محاذاة في المنتصف على الموبايل -->
<div class="text-mobile-center">
    نص في المنتصف على الموبايل
</div>
```

### عمود على الموبايل

```html
<!-- تحويل إلى عمود على الموبايل -->
<div class="d-flex flex-mobile-column">
    <div>عنصر 1</div>
    <div>عنصر 2</div>
</div>
```

---

## 🎨 أمثلة عملية

### مثال 1: صفحة حذف خطبة

```html
<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{ $sermon->title }}</h5>
    </div>
    <div class="card-body">
        <p>{{ $sermon->introduction }}</p>
    </div>
    <div class="card-footer d-flex justify-content-between flex-mobile-column gap-2">
        <a href="{{ route('sermons.show', $sermon->id) }}" class="btn btn-primary w-mobile-100">
            <i class="fas fa-eye me-2"></i>
            عرض
        </a>
        <a href="{{ route('sermons.edit', $sermon->id) }}" class="btn btn-secondary w-mobile-100">
            <i class="fas fa-edit me-2"></i>
            تعديل
        </a>
        <button class="btn btn-danger w-mobile-100" onclick="showDeleteModal('هل تريد حذف هذه الخطبة؟', function() {
            document.getElementById('delete-form-{{ $sermon->id }}').submit();
        })">
            <i class="fas fa-trash me-2"></i>
            حذف
        </button>
    </div>
</div>

<form id="delete-form-{{ $sermon->id }}" action="{{ route('sermons.destroy', $sermon->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
```

### مثال 2: نموذج مع تأكيد

```html
<form id="sermon-form" action="{{ route('sermons.store') }}" method="POST">
    @csrf
    
    <div class="form-group">
        <label class="form-label">عنوان الخطبة</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label class="form-label">المحتوى</label>
        <textarea name="content" class="form-control" rows="10" required></textarea>
    </div>
    
    <div class="d-flex justify-content-between flex-mobile-column gap-2 mt-4">
        <button type="button" class="btn btn-secondary w-mobile-100" onclick="history.back()">
            <i class="fas fa-arrow-right me-2"></i>
            رجوع
        </button>
        <button type="button" class="btn btn-primary w-mobile-100" onclick="showConfirmModal('حفظ الخطبة', 'هل تريد حفظ هذه الخطبة؟', function() {
            document.getElementById('sermon-form').submit();
        })">
            <i class="fas fa-save me-2"></i>
            حفظ
        </button>
    </div>
</form>
```

### مثال 3: عرض رسائل النجاح/الخطأ من Controller

**في Controller:**

```php
public function store(Request $request)
{
    try {
        $sermon = Sermon::create($request->all());
        return redirect()->route('sermons.index')
            ->with('success', 'تم إضافة الخطبة بنجاح!');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'حدث خطأ أثناء إضافة الخطبة: ' . $e->getMessage());
    }
}
```

**في Blade:**

```php
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessModal('{{ session('success') }}');
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showErrorModal('{{ session('error') }}');
        });
    </script>
@endif

@if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showWarningModal('{{ session('warning') }}');
        });
    </script>
@endif

@if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showInfoModal('معلومة', '{{ session('info') }}');
        });
    </script>
@endif
```

---

## 📚 الملفات المرجعية

- **Modals Component**: `resources/views/components/modals.blade.php`
- **Unified Theme CSS**: `public/css/unified-theme.css`
- **Main Layout**: `resources/views/layouts/app.blade.php`

---

## ✅ أفضل الممارسات

1. **استخدم Modals للتأكيد**: دائماً استخدم modal التأكيد قبل العمليات الحساسة (حذف، نشر، إلخ)
2. **رسائل واضحة**: اكتب رسائل واضحة ومفهومة في Modals
3. **استجابة للموبايل**: استخدم الفئات المساعدة للموبايل لضمان تجربة جيدة
4. **معالجة الأخطاء**: دائماً اعرض رسائل الخطأ باستخدام `showErrorModal`
5. **تأكيد النجاح**: اعرض رسالة نجاح بعد كل عملية ناجحة

---

**آخر تحديث**: 2024-01-15
**الإصدار**: 1.0.0

