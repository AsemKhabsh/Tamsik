# ✅ قائمة التحقق قبل النشر - منصة تمسيك

**تاريخ:** 2025-10-18  
**الإصدار:** 2.1.0

---

## 📋 قائمة التحقق الشاملة

### **1. الكود والملفات** ✅

- [x] جميع التعديلات مكتملة
- [x] معلومات المطور محدثة (م/ عاصم خبش - +967780002776)
- [x] رقم التواصل بالموقع محدث (+967770617151)
- [x] تم مسح جميع Cache
- [x] لا توجد أخطاء في الكود
- [ ] تم اختبار جميع الصفحات محلياً
- [ ] تم Commit جميع التعديلات إلى Git

### **2. ملف .env** ⚠️

- [ ] إنشاء ملف `.env.production`
- [ ] تعديل `APP_ENV=production`
- [ ] تعديل `APP_DEBUG=false`
- [ ] تعديل `APP_URL` إلى الدومين الفعلي
- [ ] إضافة معلومات قاعدة البيانات
- [ ] إضافة معلومات البريد الإلكتروني
- [ ] توليد `APP_KEY` جديد

### **3. قاعدة البيانات** ⚠️

- [ ] إنشاء قاعدة بيانات على الاستضافة
- [ ] إنشاء مستخدم قاعدة البيانات
- [ ] منح الصلاحيات الكاملة
- [ ] تسجيل معلومات الاتصال

### **4. الاستضافة** ⚠️

- [ ] شراء الاستضافة (Hostinger موصى به)
- [ ] شراء/ربط الدومين
- [ ] الحصول على معلومات cPanel
- [ ] الحصول على معلومات FTP/SSH

### **5. الأمان** ⚠️

- [ ] تفعيل SSL Certificate
- [ ] تعطيل `APP_DEBUG` في الإنتاج
- [ ] تحديث `APP_KEY`
- [ ] حماية ملف `.env`
- [ ] إعداد Firewall (إن أمكن)

### **6. الأداء** ⚠️

- [ ] تشغيل `composer install --optimize-autoloader --no-dev`
- [ ] تشغيل `php artisan config:cache`
- [ ] تشغيل `php artisan route:cache`
- [ ] تشغيل `php artisan view:cache`
- [ ] تحسين الصور

### **7. الملفات المطلوبة** ✅

الملفات التي يجب رفعها:
- [x] `app/`
- [x] `bootstrap/`
- [x] `config/`
- [x] `database/`
- [x] `public/`
- [x] `resources/`
- [x] `routes/`
- [x] `storage/`
- [x] `vendor/` (أو تثبيت عبر composer)
- [x] `.htaccess`
- [x] `artisan`
- [x] `composer.json`
- [x] `composer.lock`

الملفات التي **لا** يجب رفعها:
- [x] `.env` (المحلي)
- [x] `node_modules/`
- [x] `.git/`
- [x] `tests/`
- [x] `.phpunit.result.cache`
- [x] `README.md` (اختياري)

---

## 🚀 خطوات النشر السريعة

### **الخطوة 1: تحضير محلياً**

```bash
# 1. Commit جميع التعديلات
git add .
git commit -m "جاهز للنشر - v2.1.0"
git push origin master

# 2. تحسين الملفات
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **الخطوة 2: رفع على الاستضافة**

#### **الطريقة 1: عبر Git (موصى به)**

```bash
# في SSH/Terminal على الاستضافة
cd ~
git clone https://github.com/AsemKhabsh/Tamsik.git tamsik
cd tamsik
composer install --optimize-autoloader --no-dev
cp .env.example .env
# عدّل .env بمعلومات الإنتاج
php artisan key:generate
php artisan migrate --force
php artisan storage:link
```

#### **الطريقة 2: عبر FTP**

1. ارفع جميع الملفات إلى `~/tamsik`
2. انقل محتويات `public/` إلى `public_html/`
3. عدّل `public_html/index.php` لتحديث المسارات

### **الخطوة 3: إعداد قاعدة البيانات**

```bash
# في SSH/Terminal
cd ~/tamsik
php artisan migrate --force
php artisan db:seed --force  # اختياري
```

### **الخطوة 4: ضبط الصلاحيات**

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan storage:link
```

### **الخطوة 5: تفعيل SSL**

في cPanel:
1. SSL/TLS Status
2. Run AutoSSL

### **الخطوة 6: الاختبار**

- [ ] افتح الموقع: https://yourdomain.com
- [ ] اختبر تسجيل الدخول
- [ ] اختبر إضافة محتوى
- [ ] اختبر البحث
- [ ] اختبر على الجوال

---

## 📝 معلومات مهمة للنشر

### **معلومات الموقع:**
- **الاسم:** منصة تمسيك
- **الإصدار:** 2.1.0
- **Laravel:** 10.x
- **PHP:** 8.1+
- **MySQL:** 5.7+

### **معلومات التواصل:**
- **رقم الموقع:** +967770617151
- **البريد:** info@tamsik.org

### **معلومات المطور:**
- **الاسم:** م/ عاصم خبش
- **الهاتف:** +967780002776
- **البريد:** asemkhabash@gmail.com
- **GitHub:** https://github.com/AsemKhabsh

---

## 🔧 إعدادات مهمة

### **ملف .env للإنتاج:**

```env
APP_NAME="تمسيك"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### **.htaccess في public_html:**

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
    
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🐛 حل المشاكل

### **خطأ 500:**
```bash
chmod -R 775 storage bootstrap/cache
php artisan cache:clear
php artisan config:clear
```

### **الصور لا تظهر:**
```bash
php artisan storage:link
```

### **خطأ قاعدة البيانات:**
- تحقق من `.env`
- تحقق من صلاحيات المستخدم

---

## 📞 الدعم

**للمساعدة:**
- **المطور:** م/ عاصم خبش
- **الهاتف:** +967780002776
- **البريد:** asemkhabash@gmail.com

---

**تم بحمد الله** ✨

**الحالة:** ✅ **جاهز للنشر**

