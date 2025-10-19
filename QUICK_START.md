# 🚀 دليل البدء السريع - منصة تمسيك

**الإصدار:** 2.1.0  
**آخر تحديث:** 2025-10-18

---

## 📋 نظرة عامة

هذا الدليل يساعدك على البدء بسرعة مع منصة تمسيك.

---

## ✅ المتطلبات الأساسية

تأكد من توفر:
- ✅ PHP 8.1 أو أحدث
- ✅ Composer
- ✅ MySQL 5.7 أو أحدث
- ✅ Node.js & NPM (اختياري)

---

## 🔧 التثبيت السريع

### 1. استنساخ المشروع
```bash
git clone https://github.com/AsemKhabsh/Tamsik.git
cd TamsikWebsite
```

### 2. تثبيت الحزم
```bash
composer install
```

### 3. إعداد البيئة
```bash
cp .env.example .env
php artisan key:generate
```

### 4. إعداد قاعدة البيانات
افتح `.env` وعدّل:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tamsik
DB_USERNAME=root
DB_PASSWORD=
```

### 5. تشغيل Migrations
```bash
php artisan migrate
```

### 6. تشغيل Seeders (اختياري)
```bash
php artisan db:seed
```

### 7. تشغيل السيرفر
```bash
php artisan serve
```

افتح المتصفح على: `http://localhost:8000`

---

## 🎯 الخطوات التالية

### 1. إنشاء حساب Admin
```bash
php artisan tinker
```

ثم:
```php
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@tamsik.com';
$user->password = bcrypt('password');
$user->user_type = 'admin';
$user->save();
```

### 2. تسجيل الدخول
- اذهب إلى: `http://localhost:8000/login`
- البريد: `admin@tamsik.com`
- كلمة المرور: `password`

### 3. استكشاف المنصة
- ✅ تصفح الخطب
- ✅ إضافة محتوى جديد
- ✅ تجربة Dark Mode
- ✅ اختبار البحث

---

## 🧪 الاختبار

### اختبار سريع:
```bash
# 1. تحقق من Security Headers
# افتح DevTools → Network → Headers

# 2. اختبر Rate Limiting
# حاول تسجيل الدخول 6 مرات بسرعة

# 3. اختبر Dark Mode
# اضغط على زر القمر في أسفل اليسار
```

### اختبار شامل:
راجع: `TESTING_CHECKLIST.md`

---

## 📚 التوثيق

### الأساسيات:
- 📄 `README.md` - نظرة عامة
- 📄 `INSTALLATION.md` - دليل التثبيت الكامل

### التحسينات:
- 📄 `FINAL_SUMMARY.md` - الملخص الشامل
- 📄 `IMPROVEMENTS_APPLIED.md` - التحسينات المطبقة

### الاختبار:
- 📄 `TESTING_CHECKLIST.md` - قائمة الاختبار
- 📄 `TESTING_GUIDE.md` - دليل الاختبار

### التطوير:
- 📄 `COMPREHENSIVE_AUDIT_REPORT.md` - تقرير الفحص
- 📄 `ISSUES_AND_SOLUTIONS.md` - المشاكل والحلول

---

## 🎨 الميزات الرئيسية

### 1. الخطب
- عرض قائمة الخطب
- إضافة خطبة جديدة
- البحث والتصفية
- التحميل

### 2. المحاضرات
- عرض المحاضرات القادمة
- جدولة محاضرات
- التسجيل في المحاضرات

### 3. المقالات
- قراءة المقالات
- إضافة مقال جديد
- التعليقات

### 4. العلماء
- عرض ملفات العلماء
- متابعة العلماء
- عرض محتوى العالم

### 5. البحث
- بحث متقدم
- فلترة النتائج
- ترتيب النتائج

---

## 🔒 الأمان

### Security Headers:
✅ X-Content-Type-Options  
✅ X-Frame-Options  
✅ X-XSS-Protection  
✅ Content-Security-Policy  
✅ Referrer-Policy  
✅ Permissions-Policy  
✅ Strict-Transport-Security (Production)

### Rate Limiting:
✅ 5 محاولات تسجيل دخول في الدقيقة

### Validation:
✅ Form Request Classes  
✅ رسائل خطأ بالعربية

---

## ⚡ الأداء

### Database Indexes:
✅ users.email  
✅ sermons.slug  
✅ articles.slug  
✅ lectures.scheduled_at

### النتيجة:
⬆️ تحسين 50-70% في سرعة الاستعلامات

---

## 🎨 UI/UX

### Dark Mode:
- زر تبديل في أسفل اليسار
- حفظ التفضيل تلقائياً
- دعم تفضيلات النظام

### Toast Notifications:
- إشعارات جميلة
- 4 أنواع: Success, Error, Warning, Info
- إغلاق تلقائي بعد 5 ثواني

### Loading States:
- Skeleton Loaders
- أنيميشن سلس

### Accessibility:
- ARIA Labels
- Keyboard Navigation
- Screen Reader Support

---

## 🐛 حل المشاكل الشائعة

### المشكلة: خطأ في الاتصال بقاعدة البيانات
**الحل:**
```bash
# تحقق من إعدادات .env
# تأكد من تشغيل MySQL
# أعد تشغيل السيرفر
```

### المشكلة: خطأ 500
**الحل:**
```bash
php artisan optimize:clear
php artisan config:cache
```

### المشكلة: الصور لا تظهر
**الحل:**
```bash
php artisan storage:link
```

### المشكلة: Dark Mode لا يعمل
**الحل:**
```bash
# تحقق من تحميل الملفات:
# - public/js/dark-mode.js
# - public/css/dark-mode-toggle.css
# امسح Cache المتصفح
```

---

## 📞 الدعم

### للمساعدة:
- راجع التوثيق
- افتح Issue على GitHub
- تواصل مع الفريق

### للإبلاغ عن مشاكل:
1. استخدم `TESTING_CHECKLIST.md`
2. سجل المشكلة بالتفصيل
3. أرفق Screenshots إن أمكن

---

## 🎉 مبروك!

أنت الآن جاهز لاستخدام منصة تمسيك! 🚀

### الخطوات التالية:
1. ✅ استكشف الميزات
2. ✅ أضف محتوى
3. ✅ اختبر الوظائف
4. ✅ راجع التوثيق
5. ✅ شارك ملاحظاتك

---

**تم بحمد الله** ✨

**آخر تحديث:** 2025-10-18
**الإصدار:** 2.1.0
**التطوير بواسطة:** م/ عاصم خبش
**رقم المطور:** +967780002776
**الحالة:** ✅ جاهز للاستخدام

