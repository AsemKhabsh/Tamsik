/**
 * خادم تطبيق تمسيك - منصة إسلامية شاملة
 *
 * @description خادم Express.js لإدارة المحتوى الإسلامي (خطب، فتاوى، محاضرات)
 * @author فريق تمسيك
 * @version 1.0.0
 */

// تحميل متغيرات البيئة
require('dotenv').config();

// استيراد المكتبات المطلوبة
const express = require('express');
const path = require('path');
const cors = require('cors');
const { testConnection } = require('./config/database-adapter');
const { logger, globalErrorHandler, handleUncaughtExceptions } = require('./utils/errorHandler');

// معالجة الأخطاء غير المعالجة
handleUncaughtExceptions();

// إنشاء تطبيق Express
const app = express();

// تحديد المنفذ
const PORT = process.env.PORT || 3000;

// --- Middleware ---
app.use(cors());
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// تقديم الملفات الثابتة
app.use(express.static(path.join(__dirname, 'public')));

// إنشاء مجلد uploads إذا لم يكن موجوداً
const fs = require('fs');
const uploadsDir = path.join(__dirname, 'uploads');
if (!fs.existsSync(uploadsDir)) {
    fs.mkdirSync(uploadsDir, { recursive: true });
}

// --- مسارات API ---
// مسار الصحة العامة
app.get('/api/health', async (req, res) => {
    try {
        const dbConnected = await testConnection();
        res.json({
            success: true,
            message: 'الخادم يعمل بشكل طبيعي',
            database: dbConnected ? 'متصل' : 'غير متصل',
            timestamp: new Date().toISOString()
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            message: 'خطأ في الخادم',
            error: error.message
        });
    }
});

// استيراد المسارات (مع معالجة الأخطاء)
let authRoutes, userRoutes, scholarRoutes, fatwaRoutes, sermonRoutes, lectureRoutes, thinkerRoutes, categoryRoutes, newsletterRoutes, suggestionsRoutes;

try {
    authRoutes = require('./routes/auth');
    userRoutes = require('./routes/users');
    scholarRoutes = require('./routes/scholars');
    fatwaRoutes = require('./routes/fatwas');
    sermonRoutes = require('./routes/sermons');
    lectureRoutes = require('./routes/lectures');
    thinkerRoutes = require('./routes/thinkers');
    categoryRoutes = require('./routes/categories');
    newsletterRoutes = require('./routes/newsletter');
    suggestionsRoutes = require('./routes/suggestions');

    // استخدام المسارات
    app.use('/api/auth', authRoutes);
    app.use('/api/users', userRoutes);
    app.use('/api/scholars', scholarRoutes);
    app.use('/api/fatwas', fatwaRoutes);
    app.use('/api/sermons', sermonRoutes);
    app.use('/api/lectures', lectureRoutes);
    app.use('/api/thinkers', thinkerRoutes);
    app.use('/api/categories', categoryRoutes);
    app.use('/api/newsletter', newsletterRoutes);
    app.use('/api/suggestions', suggestionsRoutes);
} catch (error) {
    console.warn('⚠️  تحذير: بعض المسارات غير متاحة:', error.message);
}

// معالج الأخطاء العام
app.use(globalErrorHandler);

// معالج المسارات غير الموجودة
app.use('/api/*', (req, res) => {
    res.status(404).json({
        success: false,
        message: 'المسار غير موجود'
    });
});

// تقديم index.html للمسارات الأخرى (SPA support)
app.get('*', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// --- تشغيل الخادم ---
const startServer = async () => {
    try {
        // اختبار الاتصال بقاعدة البيانات
        const dbConnected = await testConnection();
        if (!dbConnected) {
            console.warn('⚠️  تحذير: لا يمكن الاتصال بقاعدة البيانات');
            console.log('💡 تأكد من تشغيل MySQL وتحديث إعدادات .env');
        }

        app.listen(PORT, () => {
            console.log(`🚀 الخادم يعمل على المنفذ http://localhost:${PORT}`);
            console.log('📱 يمكنك الآن الوصول للموقع عبر المتصفح');
            console.log(`🔗 API متاح على http://localhost:${PORT}/api`);
            if (dbConnected) {
                console.log('✅ قاعدة البيانات متصلة بنجاح');
            }
        });
    } catch (error) {
        console.error('❌ فشل في تشغيل الخادم:', error.message);
        process.exit(1);
    }
};

startServer();
