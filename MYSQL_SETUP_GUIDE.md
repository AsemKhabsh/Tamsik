# دليل تثبيت وإعداد MySQL لمشروع تمسيك

## 📋 متطلبات النظام

### Windows 10/11
- مساحة فارغة: 2 جيجابايت على الأقل
- ذاكرة RAM: 4 جيجابايت على الأقل
- صلاحيات المدير (Administrator)

## 🔽 تحميل وتثبيت MySQL

### الطريقة الأولى: MySQL Installer (مُوصى بها)

1. **تحميل MySQL Installer**
   - اذهب إلى: https://dev.mysql.com/downloads/installer/
   - اختر "mysql-installer-web-community" (حجم أصغر)
   - أو "mysql-installer-community" (تحميل كامل)

2. **تشغيل المثبت**
   - شغل الملف كمدير (Run as Administrator)
   - اختر "Developer Default" للتثبيت الكامل
   - أو "Server only" للخادم فقط

3. **إعداد كلمة المرور**
   - اختر "Use Strong Password Encryption"
   - أدخل كلمة مرور قوية للمستخدم root
   - **احفظ كلمة المرور هذه - ستحتاجها لاحقاً**

4. **إعداد الخدمة**
   - اختر "Configure MySQL Server as a Windows Service"
   - اسم الخدمة: MySQL80 (افتراضي)
   - تشغيل تلقائي: نعم

### الطريقة الثانية: XAMPP (أسهل للمبتدئين)

1. **تحميل XAMPP**
   - اذهب إلى: https://www.apachefriends.org/download.html
   - حمل النسخة الأحدث

2. **تثبيت XAMPP**
   - شغل المثبت كمدير
   - اختر المكونات: Apache, MySQL, PHP, phpMyAdmin
   - مجلد التثبيت: C:\xampp (افتراضي)

3. **تشغيل الخدمات**
   - افتح XAMPP Control Panel
   - اضغط "Start" بجانب Apache و MySQL
   - تأكد من ظهور اللون الأخضر

## ⚙️ إعداد كلمة المرور

### إذا كنت تستخدم MySQL مباشرة:

1. **فتح Command Prompt كمدير**
   ```cmd
   # الانتقال لمجلد MySQL
   cd "C:\Program Files\MySQL\MySQL Server 8.0\bin"
   
   # تسجيل الدخول (إذا لم تكن هناك كلمة مرور)
   mysql -u root
   
   # أو إذا كانت هناك كلمة مرور
   mysql -u root -p
   ```

2. **إنشاء كلمة مرور جديدة**
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_password_here';
   FLUSH PRIVILEGES;
   EXIT;
   ```

### إذا كنت تستخدم XAMPP:

1. **افتح phpMyAdmin**
   - اذهب إلى: http://localhost/phpmyadmin
   - اسم المستخدم: root
   - كلمة المرور: (فارغة افتراضياً)

2. **تغيير كلمة المرور**
   - اضغط على "User accounts"
   - اختر المستخدم "root"
   - اضغط "Change password"
   - أدخل كلمة مرور جديدة

## 🔧 تحديث ملف .env

بعد إعداد كلمة المرور، حدث ملف `.env`:

```env
# إعدادات قاعدة البيانات
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password_here  # ضع كلمة المرور هنا
DB_NAME=tamsik_db
DB_PORT=3306
```

## 🧪 اختبار الاتصال

1. **اختبار الاتصال الأساسي**
   ```bash
   npm run test-db
   ```

2. **إنشاء قاعدة البيانات**
   ```bash
   node scripts/createDatabase.js
   ```

3. **تهيئة الجداول**
   ```bash
   npm run init-db
   ```

## 🔍 استكشاف الأخطاء الشائعة

### خطأ: "Access denied for user 'root'@'localhost'"
**الحل:**
- تأكد من كلمة المرور في ملف .env
- جرب تسجيل الدخول يدوياً: `mysql -u root -p`

### خطأ: "Can't connect to MySQL server"
**الحل:**
- تأكد من تشغيل خدمة MySQL
- Windows: Services → MySQL80 → Start
- XAMPP: Control Panel → MySQL → Start

### خطأ: "mysql command not found"
**الحل:**
- أضف MySQL للمتغير PATH:
  1. Control Panel → System → Advanced → Environment Variables
  2. Path → Edit → New
  3. أضف: `C:\Program Files\MySQL\MySQL Server 8.0\bin`

### خطأ: "Port 3306 already in use"
**الحل:**
- تأكد من عدم تشغيل خدمة MySQL أخرى
- أو غير المنفذ في ملف .env

## 📱 أدوات إدارة قاعدة البيانات

### 1. phpMyAdmin (مع XAMPP)
- الرابط: http://localhost/phpmyadmin
- سهل الاستخدام للمبتدئين

### 2. MySQL Workbench
- تحميل من: https://dev.mysql.com/downloads/workbench/
- أداة رسمية من MySQL

### 3. HeidiSQL (مجاني)
- تحميل من: https://www.heidisql.com/
- خفيف وسريع

## ✅ التحقق من نجاح التثبيت

بعد إكمال التثبيت، يجب أن تحصل على:

```bash
# اختبار الاتصال
npm run test-db
# النتيجة المتوقعة: ✅ تم الاتصال بقاعدة البيانات بنجاح

# إنشاء قاعدة البيانات
node scripts/createDatabase.js
# النتيجة المتوقعة: 🎉 قاعدة البيانات جاهزة!

# تهيئة الجداول
npm run init-db
# النتيجة المتوقعة: 🎉 تم تهيئة قاعدة البيانات بنجاح!
```

## 🆘 الحصول على المساعدة

إذا واجهت مشاكل:

1. **تحقق من ملفات السجل**
   - MySQL: `C:\ProgramData\MySQL\MySQL Server 8.0\Data\*.err`
   - XAMPP: `C:\xampp\mysql\data\*.err`

2. **أعد تشغيل الخدمات**
   ```cmd
   net stop MySQL80
   net start MySQL80
   ```

3. **تحقق من المنافذ**
   ```cmd
   netstat -an | findstr 3306
   ```

## 🔄 الخطوات التالية

بعد نجاح تثبيت MySQL:

1. ✅ تحديث ملف .env بكلمة المرور الصحيحة
2. ✅ تشغيل `npm run test-db` للتأكد من الاتصال
3. ✅ تشغيل `npm run init-db` لإنشاء الجداول
4. ✅ تشغيل `npm start` لبدء الخادم
5. ✅ فتح http://localhost:3000/prepare_sermon.html

---

**ملاحظة مهمة:** احفظ كلمة مرور MySQL في مكان آمن، ستحتاجها في كل مرة تريد الوصول لقاعدة البيانات.
