# ✅ قائمة التحقق السريعة قبل النشر
## Quick Pre-Launch Checklist

**المشروع:** منصة تمسيك  
**التاريخ:** 2025-10-25  
**الحالة:** جاهز للنشر بنسبة 95%

---

## 🔴 يجب عمله قبل النشر (MUST DO)

### 1. تكوين ملف .env للإنتاج ⏱️ 30 دقيقة

```bash
# نسخ .env.example إلى .env
cp .env.example .env

# تعديل القيم التالية:
APP_NAME="Tamsik"
APP_ENV=production          # ⚠️ مهم جداً!
APP_DEBUG=false             # ⚠️ مهم جداً!
APP_URL=https://tamsik.org  # ⚠️ غيّر للدومين الحقيقي

# توليد APP_KEY
php artisan key:generate

# قاعدة البيانات
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tamsik_production
DB_USERNAME=tamsik_user
DB_PASSWORD=<كلمة-مرور-قوية-جداً>  # ⚠️ استخدم كلمة مرور قوية!

# البريد الإلكتروني
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=info@tamsik.org
MAIL_PASSWORD=<app-password>  # ⚠️ استخدم App Password من Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@tamsik.org"
MAIL_FROM_NAME="تمسيك"

# الأمان (للـ HTTPS)
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

**✅ تم:** [ ]

---

### 2. إعداد قاعدة البيانات ⏱️ 15 دقيقة

```bash
# إنشاء قاعدة البيانات
mysql -u root -p
CREATE DATABASE tamsik_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tamsik_user'@'localhost' IDENTIFIED BY '<كلمة-مرور-قوية>';
GRANT ALL PRIVILEGES ON tamsik_production.* TO 'tamsik_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# تشغيل Migrations
php artisan migrate --force

# تشغيل Seeders (الأدوار والصلاحيات)
php artisan db:seed --class=RolesAndPermissionsSeeder

# إنشاء حساب Admin
php artisan tinker
>>> $user = new App\Models\User();
>>> $user->name = 'المدير العام';
>>> $user->email = 'admin@tamsik.org';
>>> $user->password = Hash::make('كلمة-مرور-قوية-جداً');
>>> $user->is_active = true;
>>> $user->save();
>>> $user->assignRole('admin');
>>> exit
```

**✅ تم:** [ ]

---

### 3. تحسين الأداء للإنتاج ⏱️ 5 دقائق

```bash
# Cache التكوينات
php artisan config:cache

# Cache الـ Routes
php artisan route:cache

# Cache الـ Views
php artisan view:cache

# Optimize Composer
composer install --optimize-autoloader --no-dev

# إنشاء Storage Link
php artisan storage:link
```

**✅ تم:** [ ]

---

### 4. تكوين Web Server (Nginx) ⏱️ 20 دقيقة

```nginx
# /etc/nginx/sites-available/tamsik.org

server {
    listen 80;
    listen [::]:80;
    server_name tamsik.org www.tamsik.org;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name tamsik.org www.tamsik.org;

    root /var/www/tamsik/public;
    index index.php index.html;

    # SSL Configuration (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/tamsik.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tamsik.org/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers (إضافية)
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    # Laravel Configuration
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache Static Files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# تفعيل الموقع
sudo ln -s /etc/nginx/sites-available/tamsik.org /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

**✅ تم:** [ ]

---

### 5. تثبيت SSL Certificate (Let's Encrypt) ⏱️ 10 دقائق

```bash
# تثبيت Certbot
sudo apt install certbot python3-certbot-nginx

# الحصول على SSL Certificate
sudo certbot --nginx -d tamsik.org -d www.tamsik.org

# التجديد التلقائي
sudo certbot renew --dry-run
```

**✅ تم:** [ ]

---

### 6. ضبط الصلاحيات ⏱️ 5 دقائق

```bash
# صلاحيات المجلدات
sudo chown -R www-data:www-data /var/www/tamsik
sudo chmod -R 755 /var/www/tamsik
sudo chmod -R 775 /var/www/tamsik/storage
sudo chmod -R 775 /var/www/tamsik/bootstrap/cache

# حماية ملف .env
chmod 600 /var/www/tamsik/.env
```

**✅ تم:** [ ]

---

## 🟡 يُنصح به بشدة (HIGHLY RECOMMENDED)

### 7. إعداد Backup تلقائي ⏱️ 1 ساعة

```bash
# تثبيت Laravel Backup
composer require spatie/laravel-backup

# نشر التكوينات
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# تكوين Backup في config/backup.php
# ثم إضافة Cron Job:
# 0 2 * * * cd /var/www/tamsik && php artisan backup:run >> /dev/null 2>&1
```

**✅ تم:** [ ]

---

### 8. تكوين Error Tracking (Sentry) ⏱️ 30 دقيقة

```bash
# تثبيت Sentry
composer require sentry/sentry-laravel

# نشر التكوينات
php artisan vendor:publish --provider="Sentry\Laravel\ServiceProvider"

# إضافة DSN في .env
SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/project-id
```

**✅ تم:** [ ]

---

### 9. تفعيل Cloudflare CDN ⏱️ 20 دقيقة

```bash
1. إنشاء حساب على Cloudflare
2. إضافة الدومين tamsik.org
3. تغيير Nameservers عند مزود الدومين
4. تفعيل:
   - SSL/TLS (Full)
   - Auto Minify (CSS, JS, HTML)
   - Brotli Compression
   - Rocket Loader
   - Cache Everything
```

**✅ تم:** [ ]

---

### 10. إعداد Monitoring ⏱️ 30 دقيقة

```bash
# Uptime Monitoring (مجاني)
1. UptimeRobot.com
2. إضافة Monitor لـ https://tamsik.org
3. تكوين Email Alerts

# Performance Monitoring (اختياري)
1. New Relic (مجاني للـ 100GB/شهر)
2. أو Datadog
```

**✅ تم:** [ ]

---

## 🟢 اختياري (OPTIONAL)

### 11. تفعيل Redis للـ Cache ⏱️ 30 دقيقة

```bash
# تثبيت Redis
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server

# تثبيت PHP Redis Extension
sudo apt install php-redis

# تعديل .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**✅ تم:** [ ]

---

### 12. إعداد Queue Workers ⏱️ 20 دقيقة

```bash
# إنشاء Supervisor Config
sudo nano /etc/supervisor/conf.d/tamsik-worker.conf

[program:tamsik-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/tamsik/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/tamsik/storage/logs/worker.log

# تفعيل
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tamsik-worker:*
```

**✅ تم:** [ ]

---

## 📊 ملخص الوقت المطلوب

| المهمة | الوقت | الأولوية |
|--------|-------|----------|
| 1. تكوين .env | 30 دقيقة | 🔴 حرجة |
| 2. قاعدة البيانات | 15 دقيقة | 🔴 حرجة |
| 3. تحسين الأداء | 5 دقائق | 🔴 حرجة |
| 4. Web Server | 20 دقيقة | 🔴 حرجة |
| 5. SSL Certificate | 10 دقيقة | 🔴 حرجة |
| 6. الصلاحيات | 5 دقائق | 🔴 حرجة |
| 7. Backup | 1 ساعة | 🟡 مهمة |
| 8. Error Tracking | 30 دقيقة | 🟡 مهمة |
| 9. Cloudflare | 20 دقيقة | 🟡 مهمة |
| 10. Monitoring | 30 دقيقة | 🟡 مهمة |
| 11. Redis | 30 دقيقة | 🟢 اختياري |
| 12. Queue Workers | 20 دقيقة | 🟢 اختياري |

**الوقت الإجمالي:**
- 🔴 **الحد الأدنى (حرجة):** 1.5 ساعة
- 🟡 **المُوصى به:** 4 ساعات
- 🟢 **الكامل:** 5 ساعات

---

## ✅ الخطوة النهائية

### بعد إكمال كل شيء:

```bash
# 1. اختبار الموقع
curl -I https://tamsik.org

# 2. اختبار SSL
https://www.ssllabs.com/ssltest/analyze.html?d=tamsik.org

# 3. اختبار الأداء
https://pagespeed.web.dev/

# 4. اختبار الأمان
https://securityheaders.com/?q=tamsik.org

# 5. اختبار SEO
https://search.google.com/test/mobile-friendly
```

---

## 🎉 تهانينا!

**إذا أكملت جميع المهام الحرجة (🔴):**
# ✅ موقعك جاهز للنشر!

**نصيحة أخيرة:**
> راقب الموقع بعناية في أول أسبوع، وكن مستعداً لإصلاح أي مشاكل صغيرة قد تظهر.

**بالتوفيق! 🚀**

---

**تم بواسطة:** الخوارزمي  
**التاريخ:** 2025-10-25

