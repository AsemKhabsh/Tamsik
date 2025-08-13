# دليل حل المشاكل - مشروع تمسيك

## 🚨 المشاكل الشائعة وحلولها

### 1. مشاكل تشغيل VS Code

#### المشكلة: خطأ في launch.json
**الأعراض:**
- رسالة خطأ عند الضغط على F5
- فشل في تشغيل المتصفح
- خطأ في تحليل JSON

**الحل:**
```bash
# تم إصلاح ملف launch.json تلقائياً
# يمكنك الآن استخدام الخيارات التالية:
# 1. Launch Edge - Direct File (لفتح الملفات مباشرة)
# 2. Launch Edge - Local Server (لتشغيل خادم محلي)
# 3. Launch Edge - Lectures Page (لفتح صفحة المحاضرات)
# 4. Launch Edge - Test Page (لفتح صفحة الاختبار)
```

### 2. مشاكل الخادم المحلي

#### المشكلة: لا يمكن تشغيل الخادم
**الحل:**
```bash
# تشغيل خادم بسيط للملفات الثابتة
npm run serve

# أو تشغيل الخادم الكامل
npm start
# أو
npm run dev
```

#### المشكلة: المنفذ مشغول
**الحل:**
```bash
# إيقاف العمليات على المنفذ 3000
netstat -ano | findstr :3000
taskkill /PID [رقم_العملية] /F

# أو استخدام منفذ آخر
node -e "const express = require('express'); const app = express(); app.use(express.static('public')); app.use(express.static('.')); app.listen(8080, () => console.log('Server running on http://localhost:8080'));"
```

### 3. مشاكل زر إضافة المحاضرة

#### المشكلة: الزر لا يظهر
**التشخيص:**
1. افتح `simple_lecture_test.html` في المتصفح
2. جرب الأدوار المختلفة
3. تحقق من وحدة التحكم (F12)

**الحل السريع:**
```javascript
// في وحدة التحكم (Console)
lectureDebug.createTestUser('member')
lectureDebug.forceShowButton()
```

### 4. مشاكل JavaScript

#### المشكلة: أخطاء في وحدة التحكم
**التشخيص:**
```javascript
// فحص شامل
lectureDebug.fullDiagnosis()

// فحص العناصر
lectureDebug.checkElements()

// فحص المستخدم
lectureDebug.checkUserState()
```

## 🛠️ أدوات التشخيص

### 1. ملفات الاختبار
- `simple_lecture_test.html` - اختبار بسيط للزر
- `test_lecture_button.html` - اختبار متقدم
- `test_sermon_permissions.html` - اختبار الصلاحيات العام

### 2. أوامر وحدة التحكم
```javascript
// إنشاء مستخدم تجريبي
lectureDebug.createTestUser('admin')    // مشرف
lectureDebug.createTestUser('scholar')  // عالم
lectureDebug.createTestUser('member')   // خطيب

// إزالة المستخدم التجريبي
lectureDebug.removeTestUser()

// إظهار الزر يدوياً
lectureDebug.forceShowButton()

// تشخيص شامل
lectureDebug.fullDiagnosis()
```

## 🚀 خطوات التشغيل الموصى بها

### الطريقة الأولى: تشغيل مباشر
1. اضغط `F5` في VS Code
2. اختر "Launch Edge - Test Page"
3. جرب الأدوار المختلفة

### الطريقة الثانية: خادم محلي
1. افتح Terminal في VS Code
2. شغل: `npm run serve`
3. افتح: `http://localhost:3000/lectures.html`

### الطريقة الثالثة: ملف مباشر
1. انقر بزر الماوس الأيمن على `lectures.html`
2. اختر "Open with Live Server" (إذا كان مثبتاً)
3. أو افتح الملف مباشرة في المتصفح

## 📋 قائمة التحقق

### قبل التشغيل:
- [ ] تأكد من تثبيت Node.js
- [ ] شغل `npm install` لتثبيت التبعيات
- [ ] تحقق من عدم وجود أخطاء في وحدة التحكم

### عند ظهور مشاكل:
- [ ] افتح وحدة التحكم (F12)
- [ ] تحقق من رسائل الخطأ
- [ ] جرب ملفات الاختبار
- [ ] استخدم أدوات التشخيص

### للاختبار:
- [ ] جرب أدوار مختلفة للمستخدمين
- [ ] تحقق من ظهور/إخفاء الأزرار
- [ ] اختبر الوظائف المختلفة

## 🆘 إذا استمرت المشاكل

1. **أرسل رسالة الخطأ الكاملة**
2. **أرفق لقطة شاشة من وحدة التحكم**
3. **اذكر الخطوات التي قمت بها**
4. **حدد نظام التشغيل والمتصفح المستخدم**

## 📞 معلومات إضافية

- **المنافذ المستخدمة:** 3000, 8080
- **المتصفحات المدعومة:** Edge, Chrome, Firefox
- **ملفات الاختبار:** في المجلد الجذر
- **ملفات التشخيص:** `debug_lectures.js`
