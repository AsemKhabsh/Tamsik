/**
 * إنشاء الجداول المفقودة في قاعدة البيانات
 */

const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, '..', 'data', 'tamsik.db');

console.log('🔄 إنشاء الجداول المفقودة...');

const db = new sqlite3.Database(dbPath, (err) => {
    if (err) {
        console.error('❌ خطأ في الاتصال بقاعدة البيانات:', err.message);
        process.exit(1);
    } else {
        console.log('✅ تم الاتصال بقاعدة البيانات بنجاح');
    }
});

// تعريف الجداول المفقودة
const missingTables = {
    scholars: `
        CREATE TABLE IF NOT EXISTS scholars (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            name TEXT NOT NULL,
            title TEXT,
            bio TEXT,
            specialization TEXT,
            location TEXT,
            image TEXT,
            education TEXT,
            experience TEXT,
            contact_info TEXT,
            social_links TEXT,
            is_featured INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            fatwa_count INTEGER DEFAULT 0,
            rating REAL DEFAULT 0.0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    `,
    
    fatwas: `
        CREATE TABLE IF NOT EXISTS fatwas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            scholar_id INTEGER,
            category_id INTEGER,
            title TEXT NOT NULL,
            question TEXT NOT NULL,
            answer TEXT,
            questioner_name TEXT,
            questioner_email TEXT,
            status TEXT DEFAULT 'pending',
            is_featured INTEGER DEFAULT 0,
            views_count INTEGER DEFAULT 0,
            likes_count INTEGER DEFAULT 0,
            tags TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (scholar_id) REFERENCES scholars(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )
    `,
    
    thinkers: `
        CREATE TABLE IF NOT EXISTS thinkers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            name TEXT NOT NULL,
            title TEXT,
            bio TEXT,
            specialization TEXT,
            location TEXT,
            image TEXT,
            birth_date DATE,
            death_date DATE,
            education TEXT,
            works TEXT,
            achievements TEXT,
            quotes TEXT,
            books TEXT,
            social_links TEXT,
            is_featured INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        )
    `,
    
    newsletters: `
        CREATE TABLE IF NOT EXISTS newsletters (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT UNIQUE NOT NULL,
            name TEXT,
            status TEXT DEFAULT 'active',
            preferences TEXT,
            subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            unsubscribed_at DATETIME,
            verification_token TEXT,
            is_verified INTEGER DEFAULT 0
        )
    `
};

// دالة لإنشاء الجداول
async function createMissingTables() {
    return new Promise((resolve, reject) => {
        const tableNames = Object.keys(missingTables);
        let completed = 0;
        let errors = [];
        
        console.log('🔄 إنشاء الجداول المفقودة...');
        
        tableNames.forEach(tableName => {
            db.run(missingTables[tableName], (err) => {
                if (err) {
                    console.error(`❌ خطأ في إنشاء جدول ${tableName}:`, err.message);
                    errors.push({ table: tableName, error: err.message });
                } else {
                    console.log(`✅ تم إنشاء جدول ${tableName}`);
                }
                
                completed++;
                if (completed === tableNames.length) {
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

// دالة لإدراج بيانات أولية للعلماء
async function insertInitialScholars() {
    return new Promise((resolve, reject) => {
        console.log('📝 إدراج بيانات أولية للعلماء...');
        
        const scholars = [
            {
                name: 'الشيخ عبد العزيز بن باز',
                title: 'المفتي العام السابق للمملكة العربية السعودية',
                bio: 'عالم جليل ومفتي المملكة العربية السعودية السابق، رحمه الله',
                specialization: 'الفقه والعقيدة',
                location: 'المملكة العربية السعودية',
                is_featured: 1,
                is_active: 1
            },
            {
                name: 'الشيخ محمد بن صالح العثيمين',
                title: 'عالم وفقيه',
                bio: 'من كبار علماء المملكة العربية السعودية، رحمه الله',
                specialization: 'الفقه والتفسير',
                location: 'المملكة العربية السعودية',
                is_featured: 1,
                is_active: 1
            },
            {
                name: 'الشيخ عبد الله بن جبرين',
                title: 'عالم وداعية',
                bio: 'عالم جليل ومن أبرز علماء عصره، رحمه الله',
                specialization: 'الفقه والدعوة',
                location: 'المملكة العربية السعودية',
                is_featured: 1,
                is_active: 1
            }
        ];
        
        const insertScholar = db.prepare(`
            INSERT OR IGNORE INTO scholars 
            (name, title, bio, specialization, location, is_featured, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        `);
        
        scholars.forEach(scholar => {
            insertScholar.run(
                scholar.name,
                scholar.title,
                scholar.bio,
                scholar.specialization,
                scholar.location,
                scholar.is_featured,
                scholar.is_active
            );
        });
        
        insertScholar.finalize((err) => {
            if (err) {
                console.error('❌ خطأ في إدراج العلماء:', err);
                reject(err);
            } else {
                console.log('✅ تم إدراج العلماء بنجاح');
                resolve();
            }
        });
    });
}

// دالة لإدراج بيانات أولية للمفكرين
async function insertInitialThinkers() {
    return new Promise((resolve, reject) => {
        console.log('📝 إدراج بيانات أولية للمفكرين...');
        
        const thinkers = [
            {
                name: 'الإمام الغزالي',
                title: 'حجة الإسلام',
                bio: 'أبو حامد محمد بن محمد الغزالي، فيلسوف وعالم مسلم',
                specialization: 'الفلسفة والتصوف',
                location: 'خراسان',
                birth_date: '1058-01-01',
                death_date: '1111-12-19',
                is_featured: 1,
                is_active: 1
            },
            {
                name: 'ابن خلدون',
                title: 'مؤسس علم الاجتماع',
                bio: 'عبد الرحمن بن محمد بن خلدون، مؤرخ ومفكر اجتماعي',
                specialization: 'التاريخ وعلم الاجتماع',
                location: 'الأندلس',
                birth_date: '1332-05-27',
                death_date: '1406-03-17',
                is_featured: 1,
                is_active: 1
            },
            {
                name: 'الإمام الشافعي',
                title: 'إمام المذهب الشافعي',
                bio: 'محمد بن إدريس الشافعي، فقيه وإمام من أئمة المسلمين',
                specialization: 'الفقه وأصول الفقه',
                location: 'مكة المكرمة',
                birth_date: '767-01-01',
                death_date: '820-01-20',
                is_featured: 1,
                is_active: 1
            }
        ];
        
        const insertThinker = db.prepare(`
            INSERT OR IGNORE INTO thinkers 
            (name, title, bio, specialization, location, birth_date, death_date, is_featured, is_active) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        `);
        
        thinkers.forEach(thinker => {
            insertThinker.run(
                thinker.name,
                thinker.title,
                thinker.bio,
                thinker.specialization,
                thinker.location,
                thinker.birth_date,
                thinker.death_date,
                thinker.is_featured,
                thinker.is_active
            );
        });
        
        insertThinker.finalize((err) => {
            if (err) {
                console.error('❌ خطأ في إدراج المفكرين:', err);
                reject(err);
            } else {
                console.log('✅ تم إدراج المفكرين بنجاح');
                resolve();
            }
        });
    });
}

// دالة التهيئة الرئيسية
async function initializeMissingTables() {
    try {
        await createMissingTables();
        await insertInitialScholars();
        await insertInitialThinkers();
        console.log('🎉 تم إنشاء الجداول المفقودة وإدراج البيانات الأولية بنجاح!');
        return true;
    } catch (error) {
        console.error('❌ خطأ في إنشاء الجداول:', error);
        return false;
    }
}

// تشغيل التهيئة إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    initializeMissingTables()
        .then((success) => {
            db.close();
            process.exit(success ? 0 : 1);
        });
}

module.exports = { initializeMissingTables };
