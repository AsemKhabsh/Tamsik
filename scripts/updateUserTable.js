/**
 * تحديث جدول المستخدمين لإضافة الحقول المفقودة
 */

const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, '..', 'data', 'tamsik.db');

console.log('🔄 تحديث جدول المستخدمين...');

const db = new sqlite3.Database(dbPath, (err) => {
    if (err) {
        console.error('❌ خطأ في الاتصال بقاعدة البيانات:', err.message);
        process.exit(1);
    } else {
        console.log('✅ تم الاتصال بقاعدة البيانات بنجاح');
    }
});

// الحقول المفقودة في جدول users
const missingColumns = [
    'is_verified INTEGER DEFAULT 0',
    'verification_token TEXT',
    'reset_password_token TEXT',
    'reset_password_expires DATETIME',
    'profile_image TEXT',
    'bio TEXT',
    'phone TEXT',
    'location TEXT'
];

// دالة لإضافة الحقول المفقودة
async function addMissingColumns() {
    return new Promise((resolve, reject) => {
        let completed = 0;
        let errors = [];
        
        console.log('🔄 إضافة الحقول المفقودة...');
        
        missingColumns.forEach((column, index) => {
            const alterQuery = `ALTER TABLE users ADD COLUMN ${column}`;
            
            db.run(alterQuery, (err) => {
                if (err) {
                    // تجاهل خطأ "duplicate column name" لأنه يعني أن العمود موجود بالفعل
                    if (!err.message.includes('duplicate column name')) {
                        console.error(`❌ خطأ في إضافة العمود ${column}:`, err.message);
                        errors.push({ column, error: err.message });
                    } else {
                        console.log(`⚠️  العمود ${column.split(' ')[0]} موجود بالفعل`);
                    }
                } else {
                    console.log(`✅ تم إضافة العمود ${column.split(' ')[0]}`);
                }
                
                completed++;
                if (completed === missingColumns.length) {
                    if (errors.length > 0) {
                        reject(errors);
                    } else {
                        resolve();
                    }
                }
            });
        });
    });
}

// دالة لفحص بنية الجدول بعد التحديث
async function checkTableStructure() {
    return new Promise((resolve, reject) => {
        console.log('\n🔍 فحص بنية جدول users بعد التحديث:');
        
        db.all("PRAGMA table_info(users)", (err, columns) => {
            if (err) {
                console.error('❌ خطأ في فحص بنية الجدول:', err.message);
                reject(err);
            } else {
                console.log('📊 أعمدة جدول users:');
                columns.forEach(col => {
                    console.log(`  - ${col.name}: ${col.type}${col.pk ? ' (PRIMARY KEY)' : ''}${col.notnull ? ' NOT NULL' : ''}${col.dflt_value ? ` DEFAULT ${col.dflt_value}` : ''}`);
                });
                resolve();
            }
        });
    });
}

// دالة التحديث الرئيسية
async function updateUserTable() {
    try {
        await addMissingColumns();
        await checkTableStructure();
        console.log('\n🎉 تم تحديث جدول المستخدمين بنجاح!');
        return true;
    } catch (error) {
        console.error('❌ خطأ في تحديث جدول المستخدمين:', error);
        return false;
    }
}

// تشغيل التحديث إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    updateUserTable()
        .then((success) => {
            db.close();
            process.exit(success ? 0 : 1);
        });
}

module.exports = { updateUserTable };
