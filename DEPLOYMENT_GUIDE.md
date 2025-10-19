# 🚀 دليل رفع مشروع تمسيك للاستضافة

## 📋 المتطلبات الأساسية

### متطلبات الخادم:
- **PHP:** 8.1 أو أحدث
- **MySQL:** 5.7 أو أحدث
- **Apache/Nginx:** مع mod_rewrite
- **Composer:** لإدارة dependencies
- **Node.js:** (اختياري) لـ frontend assets

### Extensions مطلوبة:
```
- php-mysql
- php-mbstring
- php-xml
- php-curl
- php-zip
- php-gd
- php-json
- php-tokenizer
```

## 🎯 خيارات الاستضافة

### 1. الاستضافة المشتركة (Shared Hosting)
**مناسبة للبداية - تكلفة منخفضة**
- **أمثلة:** Hostinger, Namecheap, GoDaddy
- **السعر:** $3-10 شهرياً
- **المميزات:** سهولة الإعداد، دعم فني
- **العيوب:** أداء محدود، تحكم أقل

### 2. الخوادم الافتراضية (VPS)
**أداء أفضل - تحكم أكبر**
- **أمثلة:** DigitalOcean, Linode, Vultr
- **السعر:** $5-20 شهرياً
- **المميزات:** أداء عالي، تحكم كامل
- **العيوب:** يتطلب خبرة تقنية

### 3. الاستضافة السحابية
**مرونة عالية - دفع حسب الاستخدام**
- **أمثلة:** AWS, Google Cloud, Azure
- **السعر:** متغير حسب الاستخدام
- **المميزات:** مرونة عالية، قابلية التوسع
- **العيوب:** معقد، تكلفة متغيرة

## 📁 الملفات المطلوبة للرفع

### الملفات الأساسية:
```
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env.production
├── composer.json
├── composer.lock
└── artisan
```

### الملفات التي لا ترفع:
```
- .env (الملف المحلي)
- node_modules/
- .git/
- tests/
- .phpunit.result.cache
```

## 🔧 خطوات الرفع التفصيلية

### المرحلة 1: تحضير الملفات

1. **إنشاء أرشيف للمشروع:**
```bash
# ضغط المشروع (بدون الملفات غير المطلوبة)
zip -r tamsik-website.zip . -x "node_modules/*" ".git/*" "tests/*" ".env"
```

2. **تحضير ملف .env للإنتاج:**
```bash
# نسخ ملف .env.production وتعديله
cp .env.production .env
```

### المرحلة 2: رفع الملفات

#### للاستضافة المشتركة:
1. **رفع الملفات عبر FTP/cPanel:**
   - رفع محتويات مجلد `public/` إلى `public_html/`
   - رفع باقي الملفات خارج `public_html/`

2. **تعديل مسار index.php:**
```php
// في public/index.php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

#### للخوادم الافتراضية (VPS):
1. **رفع الملفات عبر SSH:**
```bash
scp -r tamsik-website.zip user@server:/var/www/
```

2. **فك الضغط وتعيين الصلاحيات:**
```bash
cd /var/www/
unzip tamsik-website.zip
chmod -R 755 storage bootstrap/cache
```

### المرحلة 3: إعداد قاعدة البيانات

1. **إنشاء قاعدة البيانات:**
```sql
CREATE DATABASE tamsik_production;
CREATE USER 'tamsik_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON tamsik_production.* TO 'tamsik_user'@'localhost';
FLUSH PRIVILEGES;
```

2. **تحديث ملف .env:**
```env
DB_DATABASE=tamsik_production
DB_USERNAME=tamsik_user
DB_PASSWORD=strong_password
```

### المرحلة 4: تشغيل الأوامر

```bash
# تحديث dependencies
composer install --optimize-autoloader --no-dev

# تشغيل migrations
php artisan migrate --force

# تشغيل seeders
php artisan db:seed --force

# تحسين التطبيق
php artisan config:cache
php artisan view:cache

# إنشاء symbolic link
php artisan storage:link
```

## ⚙️ إعدادات الخادم

### Apache (.htaccess):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Nginx:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/tamsik/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔐 الأمان

### 1. تأمين ملف .env:
```bash
chmod 600 .env
```

### 2. إخفاء معلومات الخادم:
```apache
# في .htaccess
ServerTokens Prod
ServerSignature Off
```

### 3. تفعيل HTTPS:
```bash
# باستخدام Let's Encrypt
certbot --apache -d yourdomain.com
```

## 📊 المراقبة والصيانة

### 1. مراقبة الأخطاء:
```bash
# مراجعة logs
tail -f storage/logs/laravel.log
```

### 2. النسخ الاحتياطي:
```bash
# نسخ احتياطي لقاعدة البيانات
mysqldump -u username -p database_name > backup.sql

# نسخ احتياطي للملفات
tar -czf backup.tar.gz /var/www/tamsik/
```

### 3. التحديثات:
```bash
# تحديث dependencies
composer update

# تشغيل migrations جديدة
php artisan migrate

# إعادة تحسين التطبيق
php artisan config:clear
php artisan config:cache
```

## 🎯 نصائح مهمة

1. **اختبر الموقع محلياً** قبل الرفع
2. **احتفظ بنسخة احتياطية** من قاعدة البيانات
3. **استخدم HTTPS** دائماً
4. **راقب الأداء** والأخطاء
5. **حدث بانتظام** للأمان

## 📞 الدعم

في حالة مواجهة مشاكل:
1. راجع logs الخادم
2. تأكد من الصلاحيات
3. تحقق من إعدادات قاعدة البيانات
4. اتصل بدعم الاستضافة

---

**الموقع جاهز للرفع! 🚀**
