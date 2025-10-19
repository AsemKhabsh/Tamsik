# 🎨 دليل الهوية البصرية - منصة تمسيك

## 📋 نظرة عامة

هذا الدليل يوضح الهوية البصرية الموحدة لمنصة تمسيك الإسلامية. جميع الصفحات يجب أن تلتزم بهذه المعايير لضمان تجربة مستخدم متسقة واحترافية.

---

## 🎨 الألوان (Colors)

### الألوان الأساسية

```css
--primary-color: #1d8a4e;        /* أخضر إسلامي - اللون الرئيسي */
--primary-dark: #0f7346;         /* أخضر داكن - للتفاعلات */
--primary-light: #28a745;        /* أخضر فاتح - للخلفيات */

--secondary-color: #d4af37;      /* ذهبي - اللون الثانوي */
--secondary-dark: #b8941f;       /* ذهبي داكن */
--secondary-light: #f0d06f;      /* ذهبي فاتح */
```

### الألوان المحايدة

```css
--dark-color: #1a3a4a;           /* أزرق داكن */
--light-color: #f8f9fa;          /* أبيض فاتح */
--accent-color: #c75c5c;         /* أحمر طوبي */
```

### ألوان النصوص

```css
--text-color: #333;              /* نص أساسي */
--text-light: #f8f9fa;           /* نص فاتح */
--text-muted: #6c757d;           /* نص باهت */
--text-dark: #212529;            /* نص داكن */
```

### ألوان الحالات

```css
--success-color: #28a745;        /* نجاح */
--warning-color: #ffc107;        /* تحذير */
--danger-color: #dc3545;         /* خطر */
--info-color: #17a2b8;           /* معلومات */
```

---

## 🔤 الخطوط (Typography)

### الخطوط الأساسية

```css
--font-primary: 'Amiri', 'Scheherazade New', serif;     /* للعناوين والنصوص الرئيسية */
--font-secondary: 'Cairo', 'Tajawal', sans-serif;       /* للنصوص الثانوية */
```

### أحجام الخطوط

```css
.fs-1 { font-size: 2.5rem; }     /* 40px */
.fs-2 { font-size: 2rem; }       /* 32px */
.fs-3 { font-size: 1.75rem; }    /* 28px */
.fs-4 { font-size: 1.5rem; }     /* 24px */
.fs-5 { font-size: 1.25rem; }    /* 20px */
.fs-6 { font-size: 1rem; }       /* 16px */
```

### أوزان الخطوط

```css
.fw-bold { font-weight: 700; }
.fw-semibold { font-weight: 600; }
.fw-normal { font-weight: 400; }
```

---

## 🔘 الأزرار (Buttons)

### الأزرار الأساسية

```html
<!-- زر أساسي -->
<button class="btn btn-primary">
    <i class="fas fa-check"></i>
    حفظ
</button>

<!-- زر ثانوي -->
<button class="btn btn-secondary">
    <i class="fas fa-edit"></i>
    تعديل
</button>

<!-- زر محدد -->
<button class="btn btn-outline-primary">
    <i class="fas fa-plus"></i>
    إضافة
</button>
```

### أحجام الأزرار

```html
<button class="btn btn-primary btn-sm">صغير</button>
<button class="btn btn-primary">عادي</button>
<button class="btn btn-primary btn-lg">كبير</button>
```

---

## 📦 البطاقات (Cards)

### بطاقة أساسية

```html
<div class="card">
    <div class="card-header">
        <h4 class="card-title">عنوان البطاقة</h4>
    </div>
    <div class="card-body">
        <p class="card-text">محتوى البطاقة هنا...</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">إجراء</button>
    </div>
</div>
```

### بطاقة مع تأثير hover

```html
<div class="card hover-lift">
    <!-- المحتوى -->
</div>
```

---

## 📝 النماذج (Forms)

### حقل إدخال

```html
<div class="form-group">
    <label for="name" class="form-label">الاسم</label>
    <input type="text" id="name" class="form-control" placeholder="أدخل الاسم">
</div>
```

### قائمة منسدلة

```html
<div class="form-group">
    <label for="category" class="form-label">التصنيف</label>
    <select id="category" class="form-control form-select">
        <option value="">اختر التصنيف</option>
        <option value="1">عقيدة</option>
        <option value="2">فقه</option>
    </select>
</div>
```

### منطقة نص

```html
<div class="form-group">
    <label for="content" class="form-label">المحتوى</label>
    <textarea id="content" class="form-control" rows="5"></textarea>
</div>
```

---

## 🏷️ الشارات (Badges)

```html
<span class="badge badge-primary">جديد</span>
<span class="badge badge-success">منشور</span>
<span class="badge badge-warning">قيد المراجعة</span>
<span class="badge badge-danger">محذوف</span>
<span class="badge badge-info">معلومة</span>
```

---

## ⚠️ التنبيهات (Alerts)

```html
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    تم الحفظ بنجاح!
</div>

<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    تحذير: يرجى التحقق من البيانات
</div>

<div class="alert alert-danger">
    <i class="fas fa-times-circle"></i>
    خطأ: فشلت العملية
</div>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    معلومة: هذا إشعار معلوماتي
</div>
```

---

## 📊 الجداول (Tables)

```html
<table class="table">
    <thead>
        <tr>
            <th>العنوان</th>
            <th>الكاتب</th>
            <th>التاريخ</th>
            <th>الإجراءات</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>خطبة الجمعة</td>
            <td>الشيخ محمد</td>
            <td>2024-01-15</td>
            <td>
                <button class="btn btn-sm btn-primary">عرض</button>
            </td>
        </tr>
    </tbody>
</table>
```

---

## 🎯 الأيقونات (Icons)

### أيقونة أساسية

```html
<span class="icon">
    <i class="fas fa-home"></i>
</span>
```

### أحجام الأيقونات

```html
<span class="icon icon-sm"><i class="fas fa-home"></i></span>
<span class="icon"><i class="fas fa-home"></i></span>
<span class="icon icon-lg"><i class="fas fa-home"></i></span>
```

### أيقونة محددة

```html
<span class="icon icon-outline">
    <i class="fas fa-star"></i>
</span>
```

---

## 📏 المسافات (Spacing)

### الهوامش (Margins)

```html
<div class="mt-1">هامش علوي صغير جداً</div>
<div class="mt-2">هامش علوي صغير</div>
<div class="mt-3">هامش علوي متوسط</div>
<div class="mt-4">هامش علوي كبير</div>
<div class="mt-5">هامش علوي كبير جداً</div>

<!-- نفس الشيء لـ mb (bottom), mr (right), ml (left) -->
```

### الحشو (Padding)

```html
<div class="pt-3 pb-3">حشو علوي وسفلي</div>
<div class="pr-4 pl-4">حشو يمين ويسار</div>
```

---

## 🎨 الخلفيات والظلال

### الخلفيات

```html
<div class="bg-primary">خلفية أساسية</div>
<div class="bg-secondary">خلفية ثانوية</div>
<div class="bg-light">خلفية فاتحة</div>
<div class="bg-white">خلفية بيضاء</div>
```

### الظلال

```html
<div class="shadow-sm">ظل صغير</div>
<div class="shadow-md">ظل متوسط</div>
<div class="shadow-lg">ظل كبير</div>
```

---

## 🔄 التحريك (Animations)

```html
<!-- تلاشي -->
<div class="fade-in">محتوى يتلاشى</div>

<!-- انزلاق للأعلى -->
<div class="slide-up">محتوى ينزلق</div>

<!-- رفع عند التمرير -->
<div class="hover-lift">محتوى يرتفع عند التمرير</div>
```

---

## 📱 الاستجابة (Responsive)

جميع العناصر مصممة لتكون متجاوبة مع جميع أحجام الشاشات:

- **Desktop (> 1200px)**: التصميم الكامل
- **Tablet (768px - 1199px)**: تصميم متوسط
- **Mobile (< 768px)**: تصميم مبسط

---

## ✅ أفضل الممارسات

1. **استخدم المتغيرات دائماً**: لا تستخدم ألوان مباشرة، استخدم `var(--primary-color)`
2. **التزم بالخطوط المحددة**: استخدم `var(--font-primary)` أو `var(--font-secondary)`
3. **استخدم الفئات الجاهزة**: بدلاً من كتابة CSS مخصص، استخدم الفئات الموجودة
4. **الاتساق**: حافظ على نفس التصميم في جميع الصفحات
5. **الوضوح**: استخدم أسماء واضحة للفئات والمعرفات

---

## 📚 الملفات المرجعية

- **الهوية البصرية الموحدة**: `public/css/unified-theme.css`
- **المتغيرات الأساسية**: `public/css/main.css`
- **التخطيط**: `public/css/layout.css`
- **الصفحة الرئيسية**: `public/css/home.css`

---

## 🔗 روابط مفيدة

- [Font Awesome Icons](https://fontawesome.com/icons)
- [Bootstrap RTL](https://getbootstrap.com/)
- [Google Fonts Arabic](https://fonts.google.com/?subset=arabic)

---

**آخر تحديث**: 2024-01-15
**الإصدار**: 1.0.0

