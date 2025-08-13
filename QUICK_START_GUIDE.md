# 🚀 دليل البدء السريع - منصة تمسيك

## ⚡ تشغيل سريع (3 خطوات)

### 1. تثبيت التبعيات
```bash
npm install
```

### 2. تشغيل الخادم
```bash
npm start
```

### 3. فتح الموقع
```
http://localhost:3000
```

## 🎯 الميزات الأساسية

### 📝 إعداد خطبة جديدة
1. اذهب إلى: `http://localhost:3000/prepare_sermon.html`
2. املأ العنوان الرئيسي
3. استخدم أزرار "اقتراحات" للحصول على محتوى جاهز
4. أكمل أقسام الخطبة
5. احفظ أو صدّر الخطبة

### 🔍 نظام الاقتراحات
- **آيات قرآنية**: مصنفة حسب السياق (أمر، وعد، إخبار)
- **أحاديث شريفة**: مع التخريج والمصدر
- **أدعية**: قرآنية ونبوية ومأثورة
- **سجع وشعر**: نصوص بلاغية للخطباء

## 🛠️ اختبار النظام

### ✅ اختبار قاعدة البيانات
```bash
node -e "require('./config/database-adapter').testConnection()"
```

### 📊 عرض إحصائيات المحتوى
```bash
node scripts/addInitialSuggestions.js
```

### 🌐 اختبار API
```bash
# اختبار اقتراحات الآيات
curl "http://localhost:3000/api/suggestions/verses?limit=3"

# اختبار اقتراحات الأدعية
curl "http://localhost:3000/api/suggestions/dua?dua_type=ثناء&limit=2"
```

## 🔧 إعدادات متقدمة

### تغيير نوع قاعدة البيانات
في ملف `.env`:
```env
# للـ SQLite (افتراضي)
DB_TYPE=sqlite

# للـ MySQL
DB_TYPE=mysql
DB_HOST=localhost
DB_USER=root
DB_PASSWORD=your_password
DB_NAME=tamsik_db
```

### إضافة محتوى جديد
```bash
# إضافة بيانات أولية إضافية
node scripts/addInitialSuggestions.js
```

## 📱 الصفحات الرئيسية

- **الرئيسية**: `http://localhost:3000/`
- **إعداد خطبة**: `http://localhost:3000/prepare_sermon.html`
- **الخطب الجاهزة**: `http://localhost:3000/sermons.html`
- **العلماء**: `http://localhost:3000/scholars.html`
- **المحاضرات**: `http://localhost:3000/lectures.html`

## 🆘 حل المشاكل الشائعة

### مشكلة قاعدة البيانات
```bash
# إعادة إنشاء قاعدة البيانات
node config/sqlite-setup.js
```

### مشكلة المنفذ مشغول
```bash
# تغيير المنفذ في .env
PORT=3001
```

### مشكلة التبعيات
```bash
# إعادة تثبيت التبعيات
rm -rf node_modules package-lock.json
npm install
```

## 📞 الدعم

إذا واجهت أي مشاكل:
1. تحقق من ملف `.env`
2. تأكد من تشغيل `npm install`
3. راجع ملف `README.md` للتفاصيل الكاملة

---

**تمسيك** - منصة إسلامية لخدمة الخطباء والدعاة
