# 🌐 دليل رفع تمسيك على Hostinger

## 📋 المتطلبات

### خطة Hostinger المطلوبة:
- **Premium Web Hosting** أو أعلى
- **PHP 8.1+** (متوفر في Hostinger)
- **MySQL Database** (متوفر)
- **SSL Certificate** (مجاني)

## 🚀 خطوات الرفع

### الخطوة 1: تحضير الملفات محلياً

```bash
# 1. تحسين المشروع للإنتاج
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan view:cache

# 2. إنشاء أرشيف للرفع
# احذف هذه المجلدات/الملفات قبل الضغط:
# - node_modules/
# - .git/
# - tests/
# - .env (الملف المحلي)
```

### الخطوة 2: الدخول إلى Hostinger hPanel

1. **تسجيل الدخول** إلى حسابك في Hostinger
2. **اختر الموقع** الذي تريد رفع المشروع عليه
3. **انقر على "Manage"** للدخول إلى hPanel

### الخطوة 3: إعداد قاعدة البيانات

#### في hPanel:
1. **اذهب إلى "Databases" → "MySQL Databases"**
2. **أنشئ قاعدة بيانات جديدة:**
   - Database Name: `u123456789_tamsik`
   - اختر كلمة مرور قوية
3. **احفظ المعلومات:**
   - Database Host: `localhost`
   - Database Name: `u123456789_tamsik`
   - Username: `u123456789_tamsik`
   - Password: `كلمة المرور التي اخترتها`

### الخطوة 4: رفع الملفات

#### الطريقة 1: File Manager (الأسهل)
1. **في hPanel اذهب إلى "Files" → "File Manager"**
2. **اذهب إلى مجلد `public_html`**
3. **احذف الملفات الافتراضية** (index.html, etc.)
4. **ارفع ملف zip المشروع**
5. **انقر بالزر الأيمن → "Extract"**

#### الطريقة 2: FTP
```
Host: ftp.yourdomain.com
Username: your-ftp-username
Password: your-ftp-password
Port: 21
```

### الخطوة 5: تعديل ملف .env

1. **في File Manager، اذهب إلى مجلد المشروع**
2. **انسخ `.env.production` إلى `.env`**
3. **عدل الملف بالمعلومات التالية:**

```env
APP_NAME="Tamsik"
APP_ENV=production
APP_KEY=base64:ndIzEn6DbtLh7o6wwUdUPXYwjp3NjKnbgeVGht02+n4=
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Aden
APP_LOCALE=ar

LOG_CHANNEL=stack
LOG_LEVEL=error

# معلومات قاعدة البيانات من Hostinger
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_tamsik
DB_USERNAME=u123456789_tamsik
DB_PASSWORD=your_database_password

# إعدادات البريد (Gmail مثال)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=info@yourdomain.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@yourdomain.com"
MAIL_FROM_NAME="تمسيك"

# إعدادات أخرى
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### الخطوة 6: تشغيل الأوامر

#### في Hostinger Terminal (إذا متوفر):
```bash
cd public_html
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

#### إذا لم يكن Terminal متوفر:
1. **أنشئ ملف `setup.php` في public_html:**

```php
<?php
// setup.php - ملف إعداد مؤقت

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "🚀 بدء إعداد تمسيك...\n<br>";

// تشغيل migrations
echo "📊 إنشاء جداول قاعدة البيانات...\n<br>";
$kernel->call('migrate', ['--force' => true]);

// تشغيل seeders
echo "🌱 إضافة البيانات التجريبية...\n<br>";
$kernel->call('db:seed', ['--force' => true]);

// إنشاء storage link
echo "🔗 إنشاء روابط التخزين...\n<br>";
$kernel->call('storage:link');

echo "✅ تم الانتهاء من الإعداد بنجاح!\n<br>";
echo "🗑️ احذف هذا الملف الآن لأسباب أمنية\n<br>";
?>
```

2. **زر الملف من المتصفح:** `yourdomain.com/setup.php`
3. **احذف الملف فور الانتهاء**

### الخطوة 7: إعداد الصلاحيات

#### في File Manager:
1. **انقر بالزر الأيمن على مجلد `storage`**
2. **اختر "Permissions" → 755**
3. **كرر نفس الشيء لـ `bootstrap/cache`**

### الخطوة 8: إعداد Domain

#### إذا كان المشروع في مجلد فرعي:
1. **أنشئ ملف `.htaccess` في public_html:**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ tamsik/public/$1 [L]
</IfModule>
```

#### إذا كان المشروع في الجذر:
1. **انقل محتويات `public/` إلى `public_html/`**
2. **عدل `index.php`:**

```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

## 🔧 إعدادات Hostinger الخاصة

### تفعيل PHP 8.1:
1. **في hPanel → "Advanced" → "PHP Configuration"**
2. **اختر PHP 8.1 أو أحدث**
3. **فعل Extensions المطلوبة:**
   - mysqli
   - pdo_mysql
   - mbstring
   - xml
   - curl
   - zip
   - gd
   - json
   - tokenizer

### تفعيل SSL:
1. **في hPanel → "Security" → "SSL/TLS"**
2. **اختر "Free SSL Certificate"**
3. **انتظر التفعيل (5-15 دقيقة)**

## ✅ اختبار الموقع

### الصفحات الأساسية:
- ✅ `yourdomain.com` - الصفحة الرئيسية
- ✅ `yourdomain.com/sermons` - الخطب
- ✅ `yourdomain.com/lectures` - المحاضرات
- ✅ `yourdomain.com/thinkers` - المفكرون

### حسابات الاختبار:
- **المدير:** admin@tamsik.com / admin123
- **الخطيب:** preacher@tamsik.com / preacher123
- **العضو:** member@tamsik.com / member123

## 🆘 حل المشاكل الشائعة

### خطأ 500:
```bash
# تحقق من صلاحيات المجلدات
chmod 755 storage -R
chmod 755 bootstrap/cache -R
```

### خطأ قاعدة البيانات:
- تأكد من صحة معلومات .env
- تأكد من إنشاء قاعدة البيانات في hPanel

### الصور لا تظهر:
```bash
php artisan storage:link
```

### خطأ في الـ routes:
```bash
php artisan route:clear
php artisan config:clear
```

## 📞 الدعم

### Hostinger Support:
- **Live Chat:** متوفر 24/7
- **Knowledge Base:** شامل ومفيد
- **Email Support:** سريع الاستجابة

### معلومات مفيدة للدعم:
- نوع الاستضافة: Premium Web Hosting
- نوع المشروع: Laravel 10.x
- PHP Version: 8.1+
- Database: MySQL

---

## 🎉 تهانينا!

موقع تمسيك الآن يعمل على Hostinger! 🚀

**الخطوات التالية:**
1. ✅ اختبر جميع الوظائف
2. ✅ غير كلمات المرور الافتراضية
3. ✅ أضف محتوى حقيقي
4. ✅ فعل النسخ الاحتياطي
5. ✅ راقب الأداء

**استمتع بموقعك الجديد! 🌟**
