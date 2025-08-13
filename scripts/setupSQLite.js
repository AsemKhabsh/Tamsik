/**
 * إعداد قاعدة بيانات SQLite للمنصة
 */

const sqlite3 = require('sqlite3').verbose();
const path = require('path');
const bcrypt = require('bcryptjs');

const DB_PATH = path.join(__dirname, '..', 'database.sqlite');

// ألوان للطباعة
const colors = {
    green: '\x1b[32m',
    red: '\x1b[31m',
    yellow: '\x1b[33m',
    blue: '\x1b[34m',
    reset: '\x1b[0m',
    bold: '\x1b[1m'
};

function log(message, color = 'reset') {
    console.log(`${colors[color]}${message}${colors.reset}`);
}

function logSuccess(message) {
    log(`✅ ${message}`, 'green');
}

function logError(message) {
    log(`❌ ${message}`, 'red');
}

function logInfo(message) {
    log(`ℹ️  ${message}`, 'blue');
}

// إنشاء قاعدة البيانات والجداول
function setupDatabase() {
    return new Promise((resolve, reject) => {
        log('🔧 بدء إعداد قاعدة بيانات SQLite...', 'bold');
        
        const db = new sqlite3.Database(DB_PATH, (err) => {
            if (err) {
                logError(`خطأ في إنشاء قاعدة البيانات: ${err.message}`);
                reject(err);
                return;
            }
            logSuccess('تم إنشاء قاعدة البيانات بنجاح');
        });

        // إنشاء جدول المستخدمين
        const createUsersTable = `
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                role TEXT DEFAULT 'member',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `;

        // إنشاء جدول الخطب
        const createSermonsTable = `
            CREATE TABLE IF NOT EXISTS sermons (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                content TEXT NOT NULL,
                category TEXT,
                user_id INTEGER,
                status TEXT DEFAULT 'draft',
                views INTEGER DEFAULT 0,
                featured BOOLEAN DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users (id)
            )
        `;

        // إنشاء جدول العلماء
        const createScholarsTable = `
            CREATE TABLE IF NOT EXISTS scholars (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                biography TEXT,
                specialization TEXT,
                birth_year INTEGER,
                death_year INTEGER,
                image_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `;

        // إنشاء جدول الفتاوى
        const createFatwasTable = `
            CREATE TABLE IF NOT EXISTS fatwas (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                question TEXT NOT NULL,
                answer TEXT NOT NULL,
                scholar_id INTEGER,
                category TEXT,
                status TEXT DEFAULT 'published',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (scholar_id) REFERENCES scholars (id)
            )
        `;

        // إنشاء جدول المحاضرات
        const createLecturesTable = `
            CREATE TABLE IF NOT EXISTS lectures (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                description TEXT,
                speaker TEXT,
                duration INTEGER,
                video_url TEXT,
                audio_url TEXT,
                category TEXT,
                views INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `;

        // إنشاء جدول المفكرين
        const createThinkersTable = `
            CREATE TABLE IF NOT EXISTS thinkers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                biography TEXT,
                field TEXT,
                image_url TEXT,
                website_url TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `;

        // إنشاء جدول التصنيفات
        const createCategoriesTable = `
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE,
                description TEXT,
                type TEXT DEFAULT 'general',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        `;

        // تنفيذ إنشاء الجداول
        db.serialize(() => {
            db.run(createUsersTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول المستخدمين: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول المستخدمين');
                }
            });

            db.run(createSermonsTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول الخطب: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول الخطب');
                }
            });

            db.run(createScholarsTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول العلماء: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول العلماء');
                }
            });

            db.run(createFatwasTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول الفتاوى: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول الفتاوى');
                }
            });

            db.run(createLecturesTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول المحاضرات: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول المحاضرات');
                }
            });

            db.run(createThinkersTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول المفكرين: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول المفكرين');
                }
            });

            db.run(createCategoriesTable, (err) => {
                if (err) {
                    logError(`خطأ في إنشاء جدول التصنيفات: ${err.message}`);
                } else {
                    logSuccess('تم إنشاء جدول التصنيفات');
                }
            });

            // إنشاء المستخدم الافتراضي
            createDefaultUser(db, () => {
                // إنشاء البيانات التجريبية
                insertSampleData(db, () => {
                    db.close((err) => {
                        if (err) {
                            logError(`خطأ في إغلاق قاعدة البيانات: ${err.message}`);
                            reject(err);
                        } else {
                            logSuccess('تم إعداد قاعدة البيانات بنجاح');
                            resolve();
                        }
                    });
                });
            });
        });
    });
}

// إنشاء المستخدم الافتراضي
async function createDefaultUser(db, callback) {
    try {
        const hashedPassword = await bcrypt.hash('admin123', 10);
        
        const insertUser = `
            INSERT OR IGNORE INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)
        `;
        
        db.run(insertUser, ['المدير', 'admin@tamsik.com', hashedPassword, 'admin'], (err) => {
            if (err) {
                logError(`خطأ في إنشاء المستخدم الافتراضي: ${err.message}`);
            } else {
                logSuccess('تم إنشاء المستخدم الافتراضي (admin@tamsik.com / admin123)');
            }
            callback();
        });
    } catch (error) {
        logError(`خطأ في تشفير كلمة المرور: ${error.message}`);
        callback();
    }
}

// إدراج بيانات تجريبية
function insertSampleData(db, callback) {
    logInfo('إدراج بيانات تجريبية...');
    
    // إدراج تصنيفات
    const categories = [
        ['العقيدة', 'مواضيع العقيدة الإسلامية'],
        ['الفقه', 'الأحكام الفقهية'],
        ['الأخلاق', 'الأخلاق والآداب الإسلامية'],
        ['السيرة النبوية', 'سيرة النبي محمد صلى الله عليه وسلم'],
        ['المناسبات', 'خطب المناسبات الدينية']
    ];
    
    const insertCategory = `INSERT OR IGNORE INTO categories (name, description) VALUES (?, ?)`;
    
    categories.forEach(([name, description]) => {
        db.run(insertCategory, [name, description], (err) => {
            if (err) {
                logError(`خطأ في إدراج تصنيف ${name}: ${err.message}`);
            }
        });
    });
    
    // لا توجد خطب افتراضية - سيتم إضافة الخطب من قبل المستخدمين
    
    logSuccess('تم إدراج البيانات التجريبية');
    callback();
}

// تشغيل الإعداد
if (require.main === module) {
    setupDatabase()
        .then(() => {
            log('🎉 تم إعداد قاعدة البيانات بنجاح!', 'green');
            process.exit(0);
        })
        .catch((error) => {
            logError(`فشل في إعداد قاعدة البيانات: ${error.message}`);
            process.exit(1);
        });
}

module.exports = { setupDatabase };
