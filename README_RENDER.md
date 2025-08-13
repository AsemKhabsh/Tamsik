# 🚀 تمسيك - منصة إسلامية شاملة

## 📋 نظرة عامة

منصة تمسيك هي منصة إسلامية متكاملة تهدف إلى مساعدة الخطباء والدعاة في إعداد وتنظيم خطبهم ودروسهم بطريقة احترافية مع نظام اقتراحات ذكي.

## 🌟 الميزات الرئيسية

- **نظام إعداد الخطب المتطور** مع اقتراحات ذكية
- **مكتبة محتوى إسلامي شاملة** (آيات، أحاديث، أدعية)
- **إدارة العلماء والفتاوى** اليمنيين
- **جدولة المحاضرات والدروس** في محافظات اليمن
- **واجهة مستخدم حديثة ومتجاوبة**

## 🛠️ التقنيات المستخدمة

- **Backend**: Node.js, Express.js
- **Database**: SQLite (افتراضي) / MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Authentication**: JWT
- **Deployment**: Render

## 🚀 النشر على Render

### المتطلبات
- حساب GitHub
- حساب Render

### خطوات النشر

1. **رفع المشروع لـ GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/username/tamsik.git
   git push -u origin main
   ```

2. **إنشاء خدمة Web على Render**
   - اذهب إلى [render.com](https://render.com)
   - اضغط "New +" → "Web Service"
   - اربط مستودع GitHub
   - اختر مشروع تمسيك

3. **إعدادات الخدمة**
   - **Name**: `tamsik-platform`
   - **Environment**: `Node`
   - **Build Command**: `npm install`
   - **Start Command**: `npm start`
   - **Plan**: `Free`

4. **متغيرات البيئة**
   ```env
   NODE_ENV=production
   PORT=10000
   DB_TYPE=sqlite
   JWT_SECRET=your_secure_jwt_secret_here
   CORS_ORIGIN=*
   ```

## 🔐 بيانات الدخول الافتراضية

- **البريد الإلكتروني**: `admin@tamsik.com`
- **كلمة المرور**: `admin123`

## 🌐 الوصول للموقع

بعد النشر على Render:
- **الرئيسية**: `https://your-app.onrender.com`
- **إعداد خطبة**: `https://your-app.onrender.com/prepare_sermon.html`
- **API Health**: `https://your-app.onrender.com/api/health`

## 📊 قاعدة البيانات

النظام يحتوي على بيانات أولية شاملة:
- **الآيات القرآنية**: 10+ آية مصنفة
- **الأحاديث الشريفة**: 6+ أحاديث مخرجة
- **الأدعية**: 11+ دعاء متنوع
- **السجع**: 2+ نص بلاغي
- **الشعر الإسلامي**: 3+ قصائد
- **الآثار والأقوال**: 2+ أثر مأثور

## 🔧 التطوير المحلي

```bash
# تثبيت التبعيات
npm install

# تشغيل التطوير
npm run dev

# تشغيل الإنتاج
npm start

# اختبار API
npm run test-api

# النسخ الاحتياطي
npm run backup
```

## 📁 هيكل المشروع

```
tamsik/
├── config/              # إعدادات قاعدة البيانات
├── models/              # نماذج قاعدة البيانات
├── routes/              # مسارات API
├── public/              # الملفات الثابتة
├── scripts/             # سكريبتات مساعدة
├── data/                # قاعدة البيانات
├── uploads/             # الملفات المرفوعة
├── render.yaml          # إعدادات Render
└── server.js            # نقطة دخول التطبيق
```

## 🔌 API Endpoints

### نظام الاقتراحات
```http
GET /api/suggestions/verses?limit=10
GET /api/suggestions/hadith?limit=5
GET /api/suggestions/dua?limit=3
```

### إدارة المحتوى
```http
GET /api/sermons
GET /api/scholars
GET /api/fatwas
GET /api/lectures
```

## 🛠️ استكشاف الأخطاء

### مشاكل شائعة:
1. **خطأ في البناء**: تحقق من package.json
2. **خطأ في التشغيل**: راجع متغيرات البيئة
3. **مشكلة في قاعدة البيانات**: تحقق من setupDatabase.js

### سجلات مفيدة:
- **Build Logs**: سجلات البناء في Render
- **Runtime Logs**: سجلات التشغيل
- **Error Logs**: سجلات الأخطاء

## 📈 مراقبة الأداء

في Render Dashboard:
- **الطلبات**: عدد الطلبات في الدقيقة
- **الاستجابة**: وقت الاستجابة
- **الأخطاء**: نسبة الأخطاء
- **الذاكرة**: استخدام الذاكرة

## 🔄 التحديثات

لتحديث الموقع:
```bash
git add .
git commit -m "Update description"
git push origin main
# Render سيقوم بالنشر التلقائي
```

## 📞 الدعم

- **التوثيق**: راجع `RENDER_DEPLOYMENT.md`
- **المشاكل**: راجع سجلات Render
- **التطوير**: راجع `README.md` الرئيسي

---

**تمسيك** - منصة إسلامية شاملة على Render 🚀

*تم إعداد المشروع خصيصاً لـ Render للنشر السهل والتلقائي* 