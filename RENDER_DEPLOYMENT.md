# 🚀 دليل النشر على Render - منصة تمسيك

## 📋 متطلبات Render

### ✅ ما تم إعداده:
- [x] ملف `render.yaml` للإعداد التلقائي
- [x] تحديث `package.json` لـ Render
- [x] إعداد متغيرات البيئة
- [x] مسار فحص الصحة `/api/health`

## 🚀 خطوات النشر على Render

### الخطوة 1: إنشاء حساب Render
1. اذهب إلى [render.com](https://render.com)
2. سجل حساب جديد
3. اربط حساب GitHub

### الخطوة 2: رفع المشروع لـ GitHub
```bash
# تهيئة Git (إذا لم يكن موجوداً)
git init

# إضافة جميع الملفات
git add .

# عمل commit
git commit -m "Initial commit for Render deployment"

# إضافة remote (استبدل بالرابط الصحيح)
git remote add origin https://github.com/username/tamsik.git

# رفع المشروع
git push -u origin main
```

### الخطوة 3: إنشاء خدمة Web على Render
1. **في لوحة تحكم Render**:
   - اضغط "New +"
   - اختر "Web Service"
   - اربط مستودع GitHub
   - اختر مشروع تمسيك

2. **إعدادات الخدمة**:
   - **Name**: `tamsik-platform`
   - **Environment**: `Node`
   - **Build Command**: `npm install`
   - **Start Command**: `npm start`
   - **Plan**: `Free` (للبداية)

### الخطوة 4: إعداد متغيرات البيئة
في Render Dashboard، أضف هذه المتغيرات:

```env
NODE_ENV=production
PORT=10000
DB_TYPE=sqlite
JWT_SECRET=your_secure_jwt_secret_here
CORS_ORIGIN=*
```

### الخطوة 5: النشر التلقائي
- Render سيقوم تلقائياً بـ:
  - تثبيت التبعيات
  - تشغيل `npm install`
  - تشغيل `npm start`
  - إنشاء رابط للموقع

## 🔧 إعدادات متقدمة

### إعداد النطاق المخصص
1. في Render Dashboard
2. اذهب إلى Settings
3. أضف Custom Domain
4. Render سيوفر SSL تلقائياً

### قاعدة البيانات (اختياري)
إذا أردت استخدام PostgreSQL:
1. أنشئ خدمة PostgreSQL في Render
2. أضف متغير `DATABASE_URL`
3. غيّر `DB_TYPE` إلى `mysql`

## 📊 مميزات Render

### ✅ المميزات:
- **نشر تلقائي** من GitHub
- **SSL مجاني** تلقائياً
- **دعم Node.js** ممتاز
- **قاعدة بيانات مجانية** (PostgreSQL)
- **مراقبة الأداء** مدمجة
- **سجلات مفصلة** للأخطاء

### 💰 الأسعار:
- **الخطة المجانية**: $0/شهر
- **الخطة المدفوعة**: $7/شهر
- **قاعدة البيانات**: $7/شهر (اختياري)

## 🔍 فحص النشر

### بعد النشر، تحقق من:
1. **الرابط الرئيسي**: `https://your-app.onrender.com`
2. **فحص الصحة**: `https://your-app.onrender.com/api/health`
3. **الواجهة الأمامية**: `https://your-app.onrender.com/prepare_sermon.html`

### بيانات الدخول:
- **البريد**: `admin@tamsik.com`
- **كلمة المرور**: `admin123`

## 🛠️ استكشاف الأخطاء

### مشاكل شائعة وحلولها:

#### 1. خطأ في البناء (Build Error)
```bash
# تحقق من package.json
# تأكد من وجود جميع التبعيات
# راجع سجلات البناء في Render
```

#### 2. خطأ في التشغيل (Runtime Error)
```bash
# تحقق من متغيرات البيئة
# راجع سجلات التطبيق في Render
# تأكد من صحة مسار فحص الصحة
```

#### 3. مشكلة في قاعدة البيانات
```bash
# تأكد من تشغيل setupDatabase.js
# تحقق من صلاحيات الملفات
# راجع سجلات قاعدة البيانات
```

## 📈 مراقبة الأداء

### في Render Dashboard:
- **الطلبات**: عدد الطلبات في الدقيقة
- **الاستجابة**: وقت الاستجابة
- **الأخطاء**: نسبة الأخطاء
- **الذاكرة**: استخدام الذاكرة

### سجلات مفيدة:
- **Build Logs**: سجلات البناء
- **Runtime Logs**: سجلات التشغيل
- **Error Logs**: سجلات الأخطاء

## 🔄 التحديثات المستقبلية

### لتحديث الموقع:
```bash
# أضف التغييرات
git add .

# عمل commit
git commit -m "Update description"

# رفع التحديثات
git push origin main

# Render سيقوم بالنشر التلقائي
```

## 🎯 النتيجة النهائية

بعد النشر ستحصل على:
- **رابط مباشر**: `https://your-app.onrender.com`
- **SSL مجاني**: حماية كاملة
- **نشر تلقائي**: من GitHub
- **مراقبة مستمرة**: للأداء والأخطاء

---

**تمسيك** - منصة إسلامية شاملة على Render 🚀 