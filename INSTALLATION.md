# دليل التثبيت والإعداد - مشروع تمسيك

## 📋 المتطلبات الأساسية

### متطلبات النظام
- **Windows 10/11** أو **macOS** أو **Linux**
- **PHP 8.1** أو أحدث
- **MySQL 5.7** أو أحدث (أو MariaDB 10.3+)
- **Composer** (مدير حزم PHP)
- **Git** (نظام التحكم في الإصدارات)
- **Node.js & npm** (اختياري للتطوير)

### الأدوات المطلوبة
- **XAMPP** أو **WAMP** أو **MAMP** (بيئة تطوير محلية)
- **VS Code** أو أي محرر نصوص مفضل
- **Postman** (لاختبار API - اختياري)

---

## 🚀 خطوات التثبيت

### 1. تثبيت XAMPP

#### تحميل وتثبيت XAMPP
```bash
# اذهب إلى الرابط التالي وحمل XAMPP
https://www.apachefriends.org/download.html

# اختر إصدار PHP 8.1 أو أحدث
# قم بتثبيته في المجلد الافتراضي C:\xampp
```

#### تشغيل XAMPP
1. افتح **XAMPP Control Panel**
2. ابدأ تشغيل **Apache** و **MySQL**
3. تأكد من أن الخدمات تعمل بشكل صحيح

### 2. إعداد PHP في متغيرات البيئة

#### Windows
```bash
# اذهب إلى System Properties > Environment Variables
# في System Variables، اختر Path واضغط Edit
# اضغط New واضف: C:\xampp\php
# اضغط OK واعد تشغيل Command Prompt
```

#### التحقق من التثبيت
```bash
php --version
# يجب أن تظهر معلومات إصدار PHP
```

### 3. تثبيت Composer

#### تحميل وتثبيت Composer
```bash
# اذهب إلى الرابط التالي
https://getcomposer.org/download/

# حمل Composer-Setup.exe (للويندوز)
# قم بتثبيته (سيكتشف PHP تلقائياً)
```

#### التحقق من التثبيت
```bash
composer --version
# يجب أن تظهر معلومات إصدار Composer
```

### 4. تثبيت Git

#### تحميل وتثبيت Git
```bash
# اذهب إلى الرابط التالي
https://git-scm.com/download

# حمل وثبت Git للنظام الخاص بك
```

#### التحقق من التثبيت
```bash
git --version
# يجب أن تظهر معلومات إصدار Git
```

---

## 📁 إعداد المشروع

### 1. استنساخ المشروع
```bash
# انتقل إلى مجلد العمل المطلوب
cd C:\xampp\htdocs

# استنسخ المشروع
git clone https://github.com/your-username/tamsik.git
cd tamsik
```

### 2. تثبيت التبعيات
```bash
# تثبيت حزم PHP
composer install

# تثبيت حزم Node.js (اختياري)
npm install
```

### 3. إعداد ملف البيئة
```bash
# انسخ ملف البيئة النموذجي
copy .env.example .env

# أو في Linux/Mac
cp .env.example .env
```

### 4. تحديث إعدادات قاعدة البيانات
افتح ملف `.env` وحدث الإعدادات التالية:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tamsik_db
DB_USERNAME=root
DB_PASSWORD=
```

### 5. إنشاء قاعدة البيانات
```sql
-- افتح phpMyAdmin من XAMPP
-- أو استخدم MySQL Command Line
CREATE DATABASE tamsik_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. توليد مفتاح التطبيق
```bash
php artisan key:generate
```

### 7. تشغيل الهجرات والبيانات التجريبية
```bash
# تشغيل الهجرات لإنشاء الجداول
php artisan migrate

# تشغيل البيانات التجريبية
php artisan db:seed
```

### 8. ربط مجلد التخزين
```bash
php artisan storage:link
```

### 9. تشغيل الخادم
```bash
php artisan serve
```

الآن يمكنك الوصول للموقع على: `http://localhost:8000`

---

## 🔧 إعدادات إضافية

### إعداد البريد الإلكتروني
حدث إعدادات البريد في ملف `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@tamsik.com"
MAIL_FROM_NAME="تمسيك"
```

### إعداد رفع الملفات
تأكد من أن المجلدات التالية قابلة للكتابة:
```bash
storage/app/public/
storage/logs/
bootstrap/cache/
```

### إعداد الصلاحيات (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 👥 الحسابات الافتراضية

بعد تشغيل البيانات التجريبية، ستتوفر الحسابات التالية:

### حساب المدير
- **البريد الإلكتروني**: admin@tamsik.com
- **كلمة المرور**: password
- **الدور**: مدير النظام

### حساب العالم
- **البريد الإلكتروني**: scholar@tamsik.com
- **كلمة المرور**: password
- **الدور**: عالم

### حساب العضو
- **البريد الإلكتروني**: member@tamsik.com
- **كلمة المرور**: password
- **الدور**: عضو

---

## 🐛 حل المشاكل الشائعة

### مشكلة Composer SSL
إذا واجهت مشاكل SSL مع Composer:
```bash
# تحديث شهادات SSL
curl -o C:\xampp\apache\bin\curl-ca-bundle.crt https://curl.se/ca/cacert.pem

# أو تعطيل التحقق مؤقتاً
composer config -g disable-tls true
composer config -g secure-http false
```

### مشكلة صلاحيات الملفات
```bash
# Windows (تشغيل كمدير)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T

# Linux/Mac
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
```

### مشكلة قاعدة البيانات
```bash
# إعادة تشغيل الهجرات
php artisan migrate:fresh --seed

# فحص الاتصال بقاعدة البيانات
php artisan tinker
DB::connection()->getPdo();
```

---

## 📞 الدعم والمساعدة

إذا واجهت أي مشاكل:

1. **تحقق من السجلات**: `storage/logs/laravel.log`
2. **راجع الوثائق**: [Laravel Documentation](https://laravel.com/docs)
3. **تواصل معنا**: info@tamsik.com

---

## ✅ التحقق من التثبيت

للتأكد من أن كل شيء يعمل بشكل صحيح:

1. ✅ الوصول للصفحة الرئيسية: `http://localhost:8000`
2. ✅ تسجيل الدخول بحساب المدير
3. ✅ الوصول للوحة الإدارة
4. ✅ إنشاء خطبة جديدة
5. ✅ رفع ملف صورة
6. ✅ البحث في الخطب

إذا تمت جميع الخطوات بنجاح، فالمشروع جاهز للاستخدام! 🎉
