# 🚀 خطوات النشر - منصة تمسيك

**تاريخ:** 2025-10-18  
**الإصدار:** 2.1.0  
**الحالة:** جاهز للنشر

---

## 📋 قبل البدء

### ✅ تأكد من:
- [x] جميع التعديلات مكتملة
- [x] تم اختبار الموقع محلياً
- [x] معلومات التواصل صحيحة
- [x] قاعدة البيانات جاهزة
- [x] لديك معلومات الاستضافة

---

## 🎯 خيارات النشر

### **الخيار 1: Hostinger (موصى به للبداية)**
- ✅ سهل الإعداد
- ✅ دعم Laravel
- ✅ سعر مناسب ($3-10/شهر)
- ✅ دعم فني عربي

### **الخيار 2: DigitalOcean/VPS**
- ✅ أداء أعلى
- ✅ تحكم كامل
- ⚠️ يتطلب خبرة تقنية

### **الخيار 3: Shared Hosting عادي**
- ✅ رخيص
- ⚠️ أداء محدود

---

## 📦 المرحلة 1: تحضير الملفات

### 1. تحديث Git Repository

```bash
# 1. تحقق من الحالة
git status

# 2. أضف جميع التعديلات
git add .

# 3. Commit
git commit -m "جاهز للنشر - الإصدار 2.1.0"

# 4. Push إلى GitHub
git push origin master
```

### 2. تحضير ملف .env للإنتاج

قم بإنشاء ملف `.env.production` بالمحتوى التالي:

```env
APP_NAME="تمسيك"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@tamsik.org
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. تحسين الملفات للإنتاج

```bash
# 1. تحديث Composer dependencies
composer install --optimize-autoloader --no-dev

# 2. تحسين Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. إنشاء symbolic link للتخزين
php artisan storage:link
```

---

## 🌐 المرحلة 2: النشر على Hostinger

### الخطوة 1: شراء الاستضافة

1. اذهب إلى: https://www.hostinger.com
2. اختر خطة **Premium** أو **Business**
3. اشترِ Domain (مثل: tamsik.com)
4. أكمل عملية الشراء

### الخطوة 2: إعداد cPanel

1. **تسجيل الدخول إلى cPanel**
2. **إنشاء قاعدة البيانات:**
   - اذهب إلى **MySQL Databases**
   - أنشئ قاعدة بيانات جديدة: `tamsik_db`
   - أنشئ مستخدم جديد
   - أضف المستخدم إلى قاعدة البيانات
   - امنح جميع الصلاحيات

### الخطوة 3: رفع الملفات

#### الطريقة 1: عبر Git (موصى به)

```bash
# في cPanel Terminal أو SSH

# 1. انتقل إلى المجلد الرئيسي
cd ~

# 2. استنسخ المشروع
git clone https://github.com/AsemKhabsh/Tamsik.git tamsik

# 3. انتقل إلى المشروع
cd tamsik

# 4. تثبيت Dependencies
composer install --optimize-autoloader --no-dev

# 5. نسخ ملف .env
cp .env.example .env

# 6. توليد App Key
php artisan key:generate

# 7. تشغيل Migrations
php artisan migrate --force

# 8. تشغيل Seeders (اختياري)
php artisan db:seed --force
```

#### الطريقة 2: عبر FTP/File Manager

1. **رفع الملفات:**
   - افتح **File Manager** في cPanel
   - ارفع جميع ملفات المشروع إلى `~/tamsik`
   - **لا ترفع** مجلد `public` بعد

2. **نقل محتويات public:**
   - انسخ محتويات مجلد `public/` إلى `public_html/`
   - احذف مجلد `public/` الأصلي

### الخطوة 4: تعديل index.php

افتح `public_html/index.php` وعدّل المسارات:

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// تعديل المسار إلى autoload.php
require __DIR__.'/../tamsik/vendor/autoload.php';

// تعديل المسار إلى bootstrap/app.php
$app = require_once __DIR__.'/../tamsik/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### الخطوة 5: تعديل .env

افتح `~/tamsik/.env` وعدّل:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=your_cpanel_database_name
DB_USERNAME=your_cpanel_database_user
DB_PASSWORD=your_cpanel_database_password
```

### الخطوة 6: ضبط الصلاحيات

```bash
# في Terminal/SSH
cd ~/tamsik

# صلاحيات storage و bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# إنشاء symbolic link
php artisan storage:link
```

### الخطوة 7: إعداد .htaccess

تأكد من وجود `.htaccess` في `public_html/`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
    
    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]
    
    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🔒 المرحلة 3: الأمان والتحسينات

### 1. تفعيل SSL (HTTPS)

في cPanel:
1. اذهب إلى **SSL/TLS Status**
2. فعّل **AutoSSL** للدومين
3. انتظر 5-10 دقائق

### 2. تحسين الأداء

```bash
# في Terminal
cd ~/tamsik

# Cache جميع الإعدادات
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer
composer dump-autoload --optimize
```

### 3. إعداد Cron Jobs

في cPanel → **Cron Jobs**:

```bash
* * * * * cd ~/tamsik && php artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ المرحلة 4: الاختبار النهائي

### 1. اختبار الموقع

- [ ] افتح: https://yourdomain.com
- [ ] تحقق من الصفحة الرئيسية
- [ ] اختبر تسجيل الدخول
- [ ] اختبر إضافة خطبة
- [ ] اختبر البحث
- [ ] اختبر Dark Mode
- [ ] اختبر على الجوال

### 2. اختبار الأمان

- [ ] تحقق من HTTPS
- [ ] اختبر Security Headers
- [ ] اختبر Rate Limiting

### 3. اختبار الأداء

- [ ] سرعة تحميل الصفحات
- [ ] تحميل الصور
- [ ] استجابة قاعدة البيانات

---

## 🐛 حل المشاكل الشائعة

### المشكلة: خطأ 500

**الحل:**
```bash
# تحقق من الصلاحيات
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# امسح Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### المشكلة: الصور لا تظهر

**الحل:**
```bash
php artisan storage:link
```

### المشكلة: خطأ في قاعدة البيانات

**الحل:**
- تحقق من معلومات `.env`
- تأكد من صلاحيات المستخدم
- شغّل Migrations مرة أخرى

---

## 📞 الدعم

### للمساعدة التقنية:
- **المطور:** م/ عاصم خبش
- **الهاتف:** +967780002776
- **البريد:** asemkhabash@gmail.com

---

**تم بحمد الله** ✨

**التطوير بواسطة:** م/ عاصم خبش  
**رقم المطور:** +967780002776  
**الحالة:** ✅ **جاهز للنشر**

