# 🚀 دليل البدء السريع - منصة تمسك

## ⚡ تشغيل سريع (بدون قاعدة بيانات)

### 1. تشغيل الخادم المبسط
```bash
node server-simple.js
```

### 2. زيارة صفحة الاختبار
افتح المتصفح وانتقل إلى:
```
http://localhost:3000/test-api.html
```

### 3. اختبار النظام
- ✅ **اختبار صحة الخادم**: يجب أن يظهر مؤشر أخضر
- 🔐 **اختبار المصادقة**: استخدم البيانات الافتراضية
  - البريد: `admin@tamsik.com`
  - كلمة المرور: `admin123`

---

## 🗄️ إعداد قاعدة البيانات (اختياري)

### المتطلبات
- MySQL Server 8.0 أو أحدث
- تشغيل MySQL على المنفذ 3306

### خطوات الإعداد

#### 1. تثبيت MySQL
- **Windows**: تحميل من [MySQL Downloads](https://dev.mysql.com/downloads/mysql/)
- **macOS**: `brew install mysql`
- **Ubuntu**: `sudo apt install mysql-server`

#### 2. تشغيل MySQL
```bash
# Windows (كخدمة)
net start mysql

# macOS/Linux
sudo systemctl start mysql
# أو
brew services start mysql
```

#### 3. إعداد كلمة مرور MySQL
```bash
mysql -u root -p
# ثم تعيين كلمة مرور جديدة
ALTER USER 'root'@'localhost' IDENTIFIED BY 'your_password';
```

#### 4. تحديث ملف .env
```env
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=tamsik_db
DB_PORT=3306
```

#### 5. إعداد قاعدة البيانات
في صفحة الاختبار، انقر على **"إعداد قاعدة البيانات"**

أو استخدم الأمر:
```bash
npm run setup-db
```

---

## 🧪 اختبار API

### مسارات الاختبار المتاحة:

#### 1. صحة الخادم
```bash
curl http://localhost:3000/api/health
```

#### 2. اختبار قاعدة البيانات
```bash
curl http://localhost:3000/api/test-db
```

#### 3. إعداد قاعدة البيانات
```bash
curl -X POST http://localhost:3000/api/setup-db
```

#### 4. اختبار المصادقة
```bash
curl -X POST http://localhost:3000/api/test-auth \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tamsik.com","password":"admin123"}'
```

---

## 📱 الصفحات المتاحة

- **الصفحة الرئيسية**: http://localhost:3000
- **صفحة الاختبار**: http://localhost:3000/test-api.html
- **صفحة العلماء**: http://localhost:3000/scholars.html
- **صفحة المفكرين**: http://localhost:3000/thinkers.html
- **صفحة الخطب**: http://localhost:3000/sermons.html
- **صفحة المحاضرات**: http://localhost:3000/lectures.html

---

## 🔧 أوامر مفيدة

```bash
# تشغيل الخادم المبسط
npm run test-server

# تشغيل الخادم العادي
npm start

# تشغيل مع إعادة التحميل التلقائي
npm run dev

# إعداد قاعدة البيانات
npm run setup-db
```

---

## ❗ استكشاف الأخطاء

### مشكلة: الخادم لا يعمل
- تأكد من أن المنفذ 3000 غير مستخدم
- تحقق من تثبيت Node.js بشكل صحيح

### مشكلة: خطأ في قاعدة البيانات
- تأكد من تشغيل MySQL Server
- تحقق من إعدادات الاتصال في ملف .env
- تأكد من صحة اسم المستخدم وكلمة المرور

### مشكلة: صفحة الاختبار لا تعمل
- تأكد من تشغيل الخادم أولاً
- تحقق من عنوان URL: http://localhost:3000/test-api.html

---

## 📞 الدعم

إذا واجهت أي مشاكل:
1. تحقق من ملف README.md للتفاصيل الكاملة
2. راجع رسائل الخطأ في وحدة التحكم
3. تأكد من تثبيت جميع المتطلبات

---

**تمسيك** - منصة إسلامية شاملة للعلماء والمفكرين اليمنيين 🕌
