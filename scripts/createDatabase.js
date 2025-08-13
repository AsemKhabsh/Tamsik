const mysql = require('mysql2/promise');
require('dotenv').config();

async function createDatabaseIfNotExists() {
    let connection;
    
    try {
        console.log('🔄 محاولة الاتصال بـ MySQL...');
        
        // محاولة الاتصال بدون تحديد قاعدة بيانات
        connection = await mysql.createConnection({
            host: process.env.DB_HOST || 'localhost',
            user: process.env.DB_USER || 'root',
            password: process.env.DB_PASSWORD || '',
            port: process.env.DB_PORT || 3306
        });
        
        console.log('✅ تم الاتصال بـ MySQL بنجاح');
        
        // إنشاء قاعدة البيانات إذا لم تكن موجودة
        const dbName = process.env.DB_NAME || 'tamsik_db';
        await connection.execute(`CREATE DATABASE IF NOT EXISTS ${dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci`);
        console.log(`✅ تم إنشاء قاعدة البيانات: ${dbName}`);
        
        // التبديل إلى قاعدة البيانات الجديدة
        await connection.execute(`USE ${dbName}`);
        console.log(`✅ تم التبديل إلى قاعدة البيانات: ${dbName}`);
        
        return connection;
        
    } catch (error) {
        console.error('❌ خطأ في إنشاء قاعدة البيانات:', error.message);
        
        if (error.code === 'ECONNREFUSED') {
            console.log('💡 MySQL غير متاح. تأكد من تثبيت وتشغيل MySQL Server');
            console.log('💡 يمكنك تحميل MySQL من: https://dev.mysql.com/downloads/mysql/');
            console.log('💡 أو تثبيته باستخدام:');
            console.log('   - Windows: تحميل MySQL Installer');
            console.log('   - macOS: brew install mysql');
            console.log('   - Ubuntu: sudo apt install mysql-server');
        } else if (error.code === 'ER_ACCESS_DENIED_ERROR') {
            console.log('💡 خطأ في المصادقة. تحقق من:');
            console.log('   - اسم المستخدم في ملف .env');
            console.log('   - كلمة المرور في ملف .env');
            console.log('   - صلاحيات المستخدم في MySQL');
        }
        
        throw error;
    }
}

async function testConnection() {
    let connection;
    
    try {
        connection = await createDatabaseIfNotExists();
        console.log('🧪 اختبار الاتصال بقاعدة البيانات...');
        
        // اختبار بسيط
        const [rows] = await connection.execute('SELECT 1 as test');
        console.log('✅ اختبار الاتصال نجح:', rows[0]);
        
        return true;
        
    } catch (error) {
        console.error('❌ فشل اختبار الاتصال:', error.message);
        return false;
        
    } finally {
        if (connection) {
            await connection.end();
        }
    }
}

// تشغيل الاختبار إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    testConnection()
        .then((success) => {
            if (success) {
                console.log('🎉 قاعدة البيانات جاهزة!');
                process.exit(0);
            } else {
                console.log('❌ فشل في إعداد قاعدة البيانات');
                process.exit(1);
            }
        })
        .catch((error) => {
            console.error('❌ خطأ غير متوقع:', error);
            process.exit(1);
        });
}

module.exports = { createDatabaseIfNotExists, testConnection };
