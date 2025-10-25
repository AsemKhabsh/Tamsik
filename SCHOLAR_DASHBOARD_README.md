# لوحة تحكم العالم - Scholar Dashboard

## 📋 نظرة عامة

تم إنشاء نظام متكامل للعلماء لإدارة الأسئلة الموجهة لهم والإجابة عليها مباشرة من خلال لوحة تحكم خاصة.

---

## ✨ المميزات

### 1. لوحة تحكم شاملة
- 📊 إحصائيات فورية (إجمالي، قيد الانتظار، منشور، مسودات)
- 📝 عرض آخر الأسئلة قيد الانتظار
- ✅ عرض آخر الإجابات المنشورة
- 🚀 إجراءات سريعة

### 2. إدارة الأسئلة
- 📋 عرض جميع الأسئلة الموجهة للعالم
- 🔍 تصفية حسب الحالة (الكل، قيد الانتظار، منشور، مسودات)
- 📄 Pagination للأسئلة الكثيرة
- 🔒 عرض أسئلة العالم فقط (أمان)

### 3. الإجابة على الأسئلة
- ✍️ واجهة سهلة للإجابة
- 📚 إضافة مراجع ومصادر
- 🏷️ إضافة وسوم (Tags)
- 💾 حفظ كمسودة أو نشر مباشر
- ✏️ تعديل الإجابات المنشورة
- 🔄 نشر/إلغاء نشر الإجابات

---

## 🚀 الوصول السريع

### للعالم:
1. تسجيل الدخول
2. النقر على اسم المستخدم في الشريط العلوي
3. اختيار **"لوحة العالم"**

### الرابط المباشر:
```
/scholar/dashboard
```

---

## 📁 الملفات المضافة/المعدلة

### ✅ Controllers
```
app/Http/Controllers/ScholarDashboardController.php (جديد)
```

### ✅ Services
```
app/Services/FatwaService.php (محدث)
- getScholarQuestions()
- getScholarQuestionsStats()
- getScholarQuestion()
```

### ✅ Models
```
app/Models/Fatwa.php (محدث)
- questioner() relationship
```

### ✅ Views
```
resources/views/scholar/layout.blade.php (جديد)
resources/views/scholar/dashboard.blade.php (جديد)
resources/views/scholar/questions/index.blade.php (جديد)
resources/views/scholar/questions/show.blade.php (جديد)
```

### ✅ Routes
```
routes/web.php (محدث)
- scholar.dashboard
- scholar.questions.index
- scholar.questions.show
- scholar.questions.answer
- scholar.questions.update
- scholar.questions.publish
- scholar.questions.unpublish
```

### ✅ Layouts
```
resources/views/layouts/app.blade.php (محدث)
- إضافة رابط لوحة العالم في القائمة المنسدلة
```

---

## 🔐 الصلاحيات

### متطلبات الوصول:
- ✅ تسجيل دخول (`auth` middleware)
- ✅ صلاحية عالم (`hasRole('scholar')` أو `user_type === 'scholar'`)

### الأمان:
- 🔒 CSRF Protection على جميع النماذج
- 🔒 Authorization على جميع الإجراءات
- 🔒 Query Scoping (العالم يرى أسئلته فقط)
- 🔒 Input Validation

---

## 🎨 التصميم

### الألوان:
- **الأساسي**: `#1d8a4e` (أخضر)
- **الثانوي**: `#0f7346` (أخضر داكن)
- **قيد الانتظار**: `#ffc107` (أصفر)
- **منشور**: `#28a745` (أخضر)
- **مسودة**: `#17a2b8` (أزرق فاتح)

### المكونات:
- ✅ Sidebar ثابت
- ✅ Top Bar
- ✅ Stat Cards
- ✅ Question Cards
- ✅ RTL Support
- ✅ Responsive Design

---

## 📊 حالات الأسئلة

### 1. قيد الانتظار (Pending)
```
is_published = false
answer = null أو ''
```

### 2. مسودة (Draft)
```
is_published = false
answer != null و != ''
```

### 3. منشور (Published)
```
is_published = true
answer != null
published_at != null
```

---

## 🛠️ التثبيت والإعداد

### 1. تنظيف الـ Cache:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. التحقق من المسارات:
```bash
php artisan route:list --name=scholar
```

### 3. التحقق من الصلاحيات:
تأكد من وجود دور "scholar" في جدول `roles`:
```sql
SELECT * FROM roles WHERE name = 'scholar';
```

### 4. إنشاء عالم تجريبي:
```php
$user = User::find(1); // أو أي معرف
$user->assignRole('scholar');
// أو
$user->user_type = 'scholar';
$user->save();
```

---

## 📝 الاستخدام

### 1. عرض الأسئلة قيد الانتظار:
```
/scholar/questions?status=pending
```

### 2. الإجابة على سؤال:
1. اختيار السؤال
2. كتابة الإجابة (50+ حرف)
3. إضافة مراجع ووسوم (اختياري)
4. اختيار: نشر مباشر أو حفظ كمسودة
5. حفظ

### 3. نشر مسودة:
1. الذهاب إلى المسودات
2. اختيار السؤال
3. النقر على "نشر الإجابة"

### 4. تعديل إجابة:
1. اختيار السؤال
2. النقر على "تعديل الإجابة"
3. تعديل النص
4. حفظ

---

## 🧪 الاختبار

### الاختبارات المطلوبة:
راجع ملف: `SCHOLAR_DASHBOARD_TEST_CHECKLIST.md`

### الاختبارات الأساسية:
```bash
# 1. اختبار الوصول
✅ تسجيل دخول كعالم
✅ الوصول إلى /scholar/dashboard

# 2. اختبار الإجابة
✅ الإجابة على سؤال جديد
✅ حفظ كمسودة
✅ نشر الإجابة

# 3. اختبار الصلاحيات
❌ محاولة الوصول كمستخدم عادي (يجب أن يُرفض)
❌ محاولة عرض سؤال لعالم آخر (يجب أن يُرفض)
```

---

## 📚 التوثيق الإضافي

### الملفات المرجعية:
- `SCHOLAR_DASHBOARD_GUIDE.md` - دليل شامل للنظام
- `SCHOLAR_DASHBOARD_TEST_CHECKLIST.md` - قائمة اختبار مفصلة

---

## 🐛 استكشاف الأخطاء

### المشكلة: لا يظهر رابط "لوحة العالم"
**الحل**: تأكد من أن المستخدم لديه دور "scholar" أو `user_type = 'scholar'`

### المشكلة: خطأ 403 عند الوصول
**الحل**: تأكد من صلاحيات المستخدم

### المشكلة: لا تظهر الأسئلة
**الحل**: تأكد من وجود أسئلة في جدول `fatwas` حيث `scholar_id` = معرف العالم

### المشكلة: خطأ في حفظ الإجابة
**الحل**: 
1. تأكد من أن الإجابة أكثر من 50 حرفاً
2. تأكد من وجود CSRF token
3. راجع ملف الـ logs: `storage/logs/laravel.log`

---

## 🔄 التطوير المستقبلي

### اقتراحات للتحسين:
- [ ] محرر نصوص غني (Rich Text Editor)
- [ ] إشعارات للعالم عند وصول أسئلة جديدة
- [ ] إحصائيات متقدمة (مشاهدات، إعجابات)
- [ ] نظام تقييم للإجابات
- [ ] إمكانية رفض أسئلة غير مناسبة
- [ ] نظام بحث متقدم
- [ ] تصدير الإجابات (PDF, Word)
- [ ] نظام تعليقات داخلي بين العالم والسائل

---

## 📞 الدعم

في حالة وجود مشاكل:
1. راجع ملف الـ logs: `storage/logs/laravel.log`
2. تأكد من صلاحيات المستخدم
3. تأكد من وجود البيانات المطلوبة في قاعدة البيانات
4. راجع التوثيق في `SCHOLAR_DASHBOARD_GUIDE.md`

---

## ✅ الحالة

**الإصدار**: 1.0  
**التاريخ**: 2025-10-25  
**الحالة**: ✅ جاهز للاستخدام  
**الاختبار**: ⏳ يحتاج اختبار

---

## 👨‍💻 المطور

تم التطوير بواسطة: Augment Agent  
التاريخ: 2025-10-25

---

**ملاحظة**: هذا النظام جاهز للاستخدام ولكن يُنصح بإجراء الاختبارات الشاملة قبل النشر في بيئة الإنتاج.

