#!/usr/bin/env node

/**
 * سكريبت النسخ الاحتياطي التلقائي - منصة تمسيك
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

// إعدادات النسخ الاحتياطي
const BACKUP_DIR = path.join(__dirname, '..', 'backups');
const DATA_DIR = path.join(__dirname, '..', 'data');
const UPLOADS_DIR = path.join(__dirname, '..', 'uploads');
const LOGS_DIR = path.join(__dirname, '..', 'logs');

// التأكد من وجود مجلد النسخ الاحتياطي
if (!fs.existsSync(BACKUP_DIR)) {
    fs.mkdirSync(BACKUP_DIR, { recursive: true });
    console.log('✅ تم إنشاء مجلد النسخ الاحتياطي');
}

// دالة لإنشاء اسم الملف مع التاريخ
function getBackupFileName(prefix) {
    const now = new Date();
    const dateStr = now.toISOString().slice(0, 19).replace(/:/g, '-');
    return `${prefix}_${dateStr}`;
}

// دالة لنسخ قاعدة البيانات
function backupDatabase() {
    try {
        const dbPath = path.join(DATA_DIR, 'tamsik.db');
        if (!fs.existsSync(dbPath)) {
            console.log('⚠️ ملف قاعدة البيانات غير موجود');
            return false;
        }

        const backupName = getBackupFileName('database');
        const backupPath = path.join(BACKUP_DIR, `${backupName}.db`);
        
        fs.copyFileSync(dbPath, backupPath);
        console.log(`✅ تم نسخ قاعدة البيانات: ${backupName}.db`);
        return true;
    } catch (error) {
        console.error('❌ خطأ في نسخ قاعدة البيانات:', error.message);
        return false;
    }
}

// دالة لنسخ الملفات المرفوعة
function backupUploads() {
    try {
        if (!fs.existsSync(UPLOADS_DIR)) {
            console.log('⚠️ مجلد الملفات المرفوعة غير موجود');
            return false;
        }

        const backupName = getBackupFileName('uploads');
        const backupPath = path.join(BACKUP_DIR, `${backupName}.tar.gz`);
        
        // استخدام tar لضغط المجلد
        execSync(`tar -czf "${backupPath}" -C "${path.dirname(UPLOADS_DIR)}" "${path.basename(UPLOADS_DIR)}"`, { stdio: 'inherit' });
        console.log(`✅ تم نسخ الملفات المرفوعة: ${backupName}.tar.gz`);
        return true;
    } catch (error) {
        console.error('❌ خطأ في نسخ الملفات المرفوعة:', error.message);
        return false;
    }
}

// دالة لنسخ السجلات
function backupLogs() {
    try {
        if (!fs.existsSync(LOGS_DIR)) {
            console.log('⚠️ مجلد السجلات غير موجود');
            return false;
        }

        const backupName = getBackupFileName('logs');
        const backupPath = path.join(BACKUP_DIR, `${backupName}.tar.gz`);
        
        execSync(`tar -czf "${backupPath}" -C "${path.dirname(LOGS_DIR)}" "${path.basename(LOGS_DIR)}"`, { stdio: 'inherit' });
        console.log(`✅ تم نسخ السجلات: ${backupName}.tar.gz`);
        return true;
    } catch (error) {
        console.error('❌ خطأ في نسخ السجلات:', error.message);
        return false;
    }
}

// دالة لحذف النسخ الاحتياطية القديمة
function cleanupOldBackups() {
    try {
        const files = fs.readdirSync(BACKUP_DIR);
        const now = new Date();
        const maxAge = 7 * 24 * 60 * 60 * 1000; // 7 أيام

        let deletedCount = 0;
        files.forEach(file => {
            const filePath = path.join(BACKUP_DIR, file);
            const stats = fs.statSync(filePath);
            const age = now - stats.mtime;

            if (age > maxAge) {
                fs.unlinkSync(filePath);
                deletedCount++;
                console.log(`🗑️ تم حذف النسخة القديمة: ${file}`);
            }
        });

        if (deletedCount > 0) {
            console.log(`✅ تم حذف ${deletedCount} نسخة قديمة`);
        } else {
            console.log('✅ لا توجد نسخ قديمة للحذف');
        }
    } catch (error) {
        console.error('❌ خطأ في حذف النسخ القديمة:', error.message);
    }
}

// دالة لعرض إحصائيات النسخ الاحتياطي
function showBackupStats() {
    try {
        const files = fs.readdirSync(BACKUP_DIR);
        const stats = {
            total: files.length,
            databases: files.filter(f => f.includes('database')).length,
            uploads: files.filter(f => f.includes('uploads')).length,
            logs: files.filter(f => f.includes('logs')).length
        };

        console.log('\n📊 إحصائيات النسخ الاحتياطي:');
        console.log(`📁 إجمالي الملفات: ${stats.total}`);
        console.log(`🗄️ قواعد البيانات: ${stats.databases}`);
        console.log(`📤 الملفات المرفوعة: ${stats.uploads}`);
        console.log(`📝 السجلات: ${stats.logs}`);

        // حساب الحجم الإجمالي
        let totalSize = 0;
        files.forEach(file => {
            const filePath = path.join(BACKUP_DIR, file);
            const stats = fs.statSync(filePath);
            totalSize += stats.size;
        });

        const sizeInMB = (totalSize / (1024 * 1024)).toFixed(2);
        console.log(`💾 الحجم الإجمالي: ${sizeInMB} MB`);
    } catch (error) {
        console.error('❌ خطأ في حساب الإحصائيات:', error.message);
    }
}

// الدالة الرئيسية
function runBackup() {
    console.log('🔄 بدء عملية النسخ الاحتياطي...');
    console.log(`📁 مجلد النسخ الاحتياطي: ${BACKUP_DIR}`);
    
    const startTime = new Date();
    
    // تنفيذ النسخ الاحتياطي
    const dbSuccess = backupDatabase();
    const uploadsSuccess = backupUploads();
    const logsSuccess = backupLogs();
    
    // حذف النسخ القديمة
    cleanupOldBackups();
    
    // عرض الإحصائيات
    showBackupStats();
    
    const endTime = new Date();
    const duration = ((endTime - startTime) / 1000).toFixed(2);
    
    console.log(`\n⏱️ وقت التنفيذ: ${duration} ثانية`);
    
    if (dbSuccess || uploadsSuccess || logsSuccess) {
        console.log('🎉 تم إكمال النسخ الاحتياطي بنجاح!');
        process.exit(0);
    } else {
        console.log('❌ فشل في النسخ الاحتياطي');
        process.exit(1);
    }
}

// تشغيل النسخ الاحتياطي إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    runBackup();
}

module.exports = {
    runBackup,
    backupDatabase,
    backupUploads,
    backupLogs,
    cleanupOldBackups
}; 