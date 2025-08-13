/**
 * تحديث جدول التصنيفات لإضافة الحقول المفقودة
 */

const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, '..', 'data', 'tamsik.db');

console.log('🔄 تحديث جدول التصنيفات...');

const db = new sqlite3.Database(dbPath, (err) => {
    if (err) {
        console.error('❌ خطأ في الاتصال بقاعدة البيانات:', err.message);
        process.exit(1);
    } else {
        console.log('✅ تم الاتصال بقاعدة البيانات بنجاح');
    }
});

// الحقول المفقودة في جدول categories
const missingColumns = [
    'slug TEXT',
    'type TEXT DEFAULT "general"',
    'parent_id INTEGER',
    'sort_order INTEGER DEFAULT 0',
    'is_active INTEGER DEFAULT 1'
];

// دالة لإضافة الحقول المفقودة
async function addMissingColumns() {
    return new Promise((resolve, reject) => {
        let completed = 0;
        let errors = [];
        
        console.log('🔄 إضافة الحقول المفقودة...');
        
        missingColumns.forEach((column, index) => {
            const alterQuery = `ALTER TABLE categories ADD COLUMN ${column}`;
            
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

// دالة لتحديث البيانات الموجودة
async function updateExistingData() {
    return new Promise((resolve, reject) => {
        console.log('🔄 تحديث البيانات الموجودة...');
        
        // تحديث slug للفئات الموجودة
        db.all('SELECT id, name FROM categories WHERE slug IS NULL', (err, rows) => {
            if (err) {
                console.error('❌ خطأ في استعلام الفئات:', err.message);
                reject(err);
                return;
            }
            
            if (rows.length === 0) {
                console.log('✅ جميع الفئات لديها slug');
                resolve();
                return;
            }
            
            let completed = 0;
            rows.forEach(row => {
                // إنشاء slug من الاسم
                const slug = row.name
                    .toLowerCase()
                    .replace(/\s+/g, '-')
                    .replace(/[^\w\-]+/g, '')
                    .replace(/\-\-+/g, '-')
                    .replace(/^-+/, '')
                    .replace(/-+$/, '');
                
                db.run('UPDATE categories SET slug = ?, type = ? WHERE id = ?', 
                    [slug, 'general', row.id], (err) => {
                    if (err) {
                        console.error(`❌ خطأ في تحديث الفئة ${row.name}:`, err.message);
                    } else {
                        console.log(`✅ تم تحديث الفئة ${row.name}`);
                    }
                    
                    completed++;
                    if (completed === rows.length) {
                        resolve();
                    }
                });
            });
        });
    });
}

// دالة لفحص بنية الجدول بعد التحديث
async function checkTableStructure() {
    return new Promise((resolve, reject) => {
        console.log('\n🔍 فحص بنية جدول categories بعد التحديث:');
        
        db.all("PRAGMA table_info(categories)", (err, columns) => {
            if (err) {
                console.error('❌ خطأ في فحص بنية الجدول:', err.message);
                reject(err);
            } else {
                console.log('📊 أعمدة جدول categories:');
                columns.forEach(col => {
                    console.log(`  - ${col.name}: ${col.type}${col.pk ? ' (PRIMARY KEY)' : ''}${col.notnull ? ' NOT NULL' : ''}${col.dflt_value ? ` DEFAULT ${col.dflt_value}` : ''}`);
                });
                resolve();
            }
        });
    });
}

// دالة التحديث الرئيسية
async function updateCategoriesTable() {
    try {
        await addMissingColumns();
        await updateExistingData();
        await checkTableStructure();
        console.log('\n🎉 تم تحديث جدول التصنيفات بنجاح!');
        return true;
    } catch (error) {
        console.error('❌ خطأ في تحديث جدول التصنيفات:', error);
        return false;
    }
}

// تشغيل التحديث إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    updateCategoriesTable()
        .then((success) => {
            db.close();
            process.exit(success ? 0 : 1);
        });
}

module.exports = { updateCategoriesTable };
