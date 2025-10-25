# إصلاح الأخطاء - Scholar Dashboard Bug Fixes

## التاريخ: 2025-10-25

---

## 🐛 المشاكل التي تم إصلاحها

### 1. خطأ في مسار الملف الشخصي (Profile Route Error)

#### المشكلة:
```
Route [profile.show] not defined
```

عند الوصول إلى `/scholar/dashboard`، كان هناك خطأ في المسار `profile.show` في ملف `scholar/layout.blade.php`.

#### السبب:
استخدام مسار غير موجود `profile.show` بدلاً من المسار الصحيح `profile`.

#### الحل:
تم تعديل الملف `resources/views/scholar/layout.blade.php`:

**قبل:**
```blade
<a class="nav-link" href="{{ route('profile.show') }}">
    <i class="fas fa-user"></i>
    الملف الشخصي
</a>
```

**بعد:**
```blade
<a class="nav-link" href="{{ route('profile') }}">
    <i class="fas fa-user"></i>
    الملف الشخصي
</a>
```

#### الملف المعدل:
- `resources/views/scholar/layout.blade.php` (السطر 215)

---

### 2. إخفاء زر "اطرح سؤال" للأدمن والعلماء

#### المشكلة:
كان زر "اطرح سؤال" يظهر لجميع المستخدمين بما في ذلك الأدمن والعلماء، وهذا غير منطقي لأن:
- الأدمن يدير الأسئلة ولا يطرحها
- العالم يجيب على الأسئلة ولا يطرحها

#### الحل:
تم إضافة شرط لإخفاء الزر عن الأدمن والعلماء في 4 ملفات:

#### 1. `resources/views/fatwas/index.blade.php`

**القسم الأول - عند عدم وجود فتاوى:**
```blade
@auth
    @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
        <a href="{{ route('questions.ask') }}" class="btn btn-primary">
            <i class="fas fa-question-circle me-2"></i>
            اطرح سؤالاً
        </a>
    @endif
@else
    <a href="{{ route('login') }}" class="btn btn-outline-primary">
        <i class="fas fa-sign-in-alt me-2"></i>
        سجل دخولك لطرح سؤال
    </a>
@endauth
```

**القسم الثاني - قسم "اطرح سؤال" في الأسفل:**
```blade
@auth
    @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
        <div class="ask-question-section mt-5 p-4 text-center" style="background: linear-gradient(135deg, #1d8a4e, #0f7346); border-radius: 10px; color: white;">
            <h3 class="mb-3">
                <i class="fas fa-question-circle me-2"></i>
                هل لديك سؤال؟
            </h3>
            <p class="mb-4">اطرح سؤالك وسيجيب عليه أحد علمائنا الأفاضل</p>
            <a href="{{ route('questions.ask') }}" class="btn btn-light btn-lg">
                <i class="fas fa-pen me-2"></i>
                اطرح سؤالك الآن
            </a>
        </div>
    @endif
@else
    <div class="ask-question-section mt-5 p-4 text-center" style="background: linear-gradient(135deg, #1d8a4e, #0f7346); border-radius: 10px; color: white;">
        <h3 class="mb-3">
            <i class="fas fa-question-circle me-2"></i>
            هل لديك سؤال؟
        </h3>
        <p class="mb-4">اطرح سؤالك وسيجيب عليه أحد علمائنا الأفاضل</p>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>
            سجل دخولك لطرح سؤال
        </a>
    </div>
@endauth
```

#### 2. `resources/views/fatwas/show.blade.php`

**في الـ Sidebar:**
```blade
@auth
    @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
        <div class="sidebar-card bg-success text-white">
            <h4 class="sidebar-title text-white">
                <i class="fas fa-question-circle me-2"></i>
                هل لديك سؤال؟
            </h4>
            <p class="mb-3">اطرح سؤالك وسيجيب عليه أحد علمائنا</p>
            <a href="{{ route('questions.ask') }}" class="btn btn-light w-100">
                <i class="fas fa-pen me-2"></i>
                اطرح سؤالك
            </a>
        </div>
    @endif
@else
    <div class="sidebar-card bg-success text-white">
        <h4 class="sidebar-title text-white">
            <i class="fas fa-question-circle me-2"></i>
            هل لديك سؤال؟
        </h4>
        <p class="mb-3">اطرح سؤالك وسيجيب عليه أحد علمائنا</p>
        <a href="{{ route('login') }}" class="btn btn-light w-100">
            <i class="fas fa-sign-in-alt me-2"></i>
            سجل دخولك
        </a>
    </div>
@endauth
```

#### 3. `resources/views/fatwas/by-scholar.blade.php`

**قسم "اطرح سؤال" في الأسفل:**
```blade
@auth
    @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
        <div class="ask-question-section mt-5 p-4 text-center" style="background: linear-gradient(135deg, #1d8a4e, #0f7346); border-radius: 10px; color: white;">
            <h3 class="mb-3">
                <i class="fas fa-question-circle me-2"></i>
                هل لديك سؤال؟
            </h3>
            <p class="mb-4">اطرح سؤالك وسيجيب عليه أحد علمائنا الأفاضل</p>
            <a href="{{ route('questions.ask') }}" class="btn btn-light btn-lg">
                <i class="fas fa-pen me-2"></i>
                اطرح سؤالك الآن
            </a>
        </div>
    @endif
@else
    <div class="ask-question-section mt-5 p-4 text-center" style="background: linear-gradient(135deg, #1d8a4e, #0f7346); border-radius: 10px; color: white;">
        <h3 class="mb-3">
            <i class="fas fa-question-circle me-2"></i>
            هل لديك سؤال؟
        </h3>
        <p class="mb-4">اطرح سؤالك وسيجيب عليه أحد علمائنا الأفاضل</p>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i>
            سجل دخولك لطرح سؤال
        </a>
    </div>
@endauth
```

---

## 📋 الملفات المعدلة

### 1. ملفات لوحة العالم:
- ✅ `resources/views/scholar/layout.blade.php`

### 2. ملفات صفحات الفتاوى:
- ✅ `resources/views/fatwas/index.blade.php`
- ✅ `resources/views/fatwas/show.blade.php`
- ✅ `resources/views/fatwas/by-scholar.blade.php`

---

## 🔍 منطق الإخفاء

### الشرط المستخدم:
```blade
@if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar')
```

### التفسير:
الزر يظهر فقط إذا كان المستخدم:
- ✅ ليس أدمن (`!hasRole('admin')`)
- ✅ ليس عالم بدور (`!hasRole('scholar')`)
- ✅ ليس عالم بنوع (`user_type !== 'scholar'`)

### من يرى الزر:
- ✅ المستخدمون العاديون (member)
- ✅ الوعاظ (preacher)
- ✅ المفكرون (thinker)
- ✅ مدخلو البيانات (data_entry)

### من لا يرى الزر:
- ❌ الأدمن (admin)
- ❌ العلماء (scholar)

---

## ✅ الاختبار

### اختبار 1: لوحة العالم
```
1. تسجيل دخول كعالم
2. الوصول إلى /scholar/dashboard
3. ✅ يجب أن تعمل الصفحة بدون أخطاء
4. ✅ يجب أن يعمل رابط "الملف الشخصي" في الـ Sidebar
```

### اختبار 2: إخفاء زر "اطرح سؤال" للأدمن
```
1. تسجيل دخول كأدمن
2. الوصول إلى /fatwas
3. ✅ يجب أن لا يظهر قسم "اطرح سؤال"
4. الوصول إلى /fatwas/{id}
5. ✅ يجب أن لا يظهر زر "اطرح سؤال" في الـ Sidebar
```

### اختبار 3: إخفاء زر "اطرح سؤال" للعالم
```
1. تسجيل دخول كعالم
2. الوصول إلى /fatwas
3. ✅ يجب أن لا يظهر قسم "اطرح سؤال"
4. الوصول إلى /fatwas/{id}
5. ✅ يجب أن لا يظهر زر "اطرح سؤال" في الـ Sidebar
```

### اختبار 4: ظهور الزر للمستخدمين العاديين
```
1. تسجيل دخول كمستخدم عادي
2. الوصول إلى /fatwas
3. ✅ يجب أن يظهر قسم "اطرح سؤال"
4. الوصول إلى /fatwas/{id}
5. ✅ يجب أن يظهر زر "اطرح سؤال" في الـ Sidebar
```

### اختبار 5: ظهور الزر للزوار (غير مسجلي الدخول)
```
1. عدم تسجيل الدخول
2. الوصول إلى /fatwas
3. ✅ يجب أن يظهر قسم "اطرح سؤال" مع زر "سجل دخولك"
4. الوصول إلى /fatwas/{id}
5. ✅ يجب أن يظهر زر "سجل دخولك" في الـ Sidebar
```

---

## 🔧 الأوامر المستخدمة

```bash
# تنظيف الـ Cache
php artisan view:clear

# التحقق من المسارات
php artisan route:list --name=profile
php artisan route:list --name=scholar
```

---

## 📝 ملاحظات

### 1. الأدوار المستخدمة:
- `admin` - الأدمن
- `scholar` - العالم (من خلال Spatie Permission)
- `user_type = 'scholar'` - العالم (من خلال حقل user_type)

### 2. لماذا نتحقق من كلا الطريقتين؟
```blade
!Auth::user()->hasRole('scholar') && Auth::user()->user_type !== 'scholar'
```

لأن النظام يدعم طريقتين لتحديد العالم:
- **Spatie Permission**: `hasRole('scholar')`
- **User Type Field**: `user_type = 'scholar'`

### 3. الأولوية:
الشرط يستخدم `&&` (AND) مما يعني أن المستخدم يجب أن لا يكون عالماً بأي من الطريقتين.

---

## ✅ الحالة

**التاريخ**: 2025-10-25  
**الحالة**: ✅ تم الإصلاح  
**الاختبار**: ⏳ يحتاج اختبار  

---

## 🎯 النتيجة

- ✅ لوحة العالم تعمل بدون أخطاء
- ✅ زر "اطرح سؤال" مخفي عن الأدمن والعلماء
- ✅ زر "اطرح سؤال" يظهر للمستخدمين العاديين والزوار
- ✅ تجربة مستخدم أفضل ومنطقية

---

**المطور**: Augment Agent  
**التاريخ**: 2025-10-25

