/**
 * إنشاء جدول المحاضرات والدروس مع بيانات أولية
 */

const { pool } = require('../config/database-adapter');

async function createLecturesTable() {
    try {
        console.log('🔄 إنشاء جدول المحاضرات...');

        // إنشاء الجدول
        const createTableQuery = `
            CREATE TABLE IF NOT EXISTS lectures (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER,
                title TEXT NOT NULL,
                lecturer_name TEXT NOT NULL,
                type TEXT NOT NULL DEFAULT 'محاضرة',
                province TEXT NOT NULL,
                location TEXT NOT NULL,
                day_of_week TEXT NOT NULL,
                time TEXT NOT NULL,
                description TEXT,
                contact_info TEXT,
                is_recurring INTEGER DEFAULT 1,
                is_active INTEGER DEFAULT 1,
                created_at TEXT DEFAULT (datetime('now')),
                updated_at TEXT DEFAULT (datetime('now'))
            )
        `;

        await pool.execute(createTableQuery);
        console.log('✅ تم إنشاء جدول المحاضرات بنجاح');

        // إضافة بيانات أولية
        await addInitialLectures();

    } catch (error) {
        console.error('❌ خطأ في إنشاء جدول المحاضرات:', error);
        throw error;
    }
}

async function addInitialLectures() {
    console.log('📚 إضافة بيانات أولية للمحاضرات...');

    const initialLectures = [
        {
            title: 'دروس في التفسير',
            lecturer_name: 'الشيخ أحمد الزبيدي',
            type: 'درس',
            province: 'صنعاء',
            location: 'الجامع الكبير',
            day_of_week: 'الجمعة',
            time: '15:30',
            description: 'دروس أسبوعية في تفسير القرآن الكريم',
            contact_info: '777123456'
        },
        {
            title: 'محاضرة في السيرة النبوية',
            lecturer_name: 'الدكتور محمد الحوثي',
            type: 'محاضرة',
            province: 'صنعاء',
            location: 'مسجد الإيمان',
            day_of_week: 'الثلاثاء',
            time: '20:00',
            description: 'محاضرات شهرية في السيرة النبوية الشريفة',
            contact_info: '777234567'
        },
        {
            title: 'دروس الفقه الإسلامي',
            lecturer_name: 'الشيخ عبدالله الحكيمي',
            type: 'درس',
            province: 'تعز',
            location: 'مسجد المعتصم',
            day_of_week: 'الأربعاء',
            time: '19:00',
            description: 'دروس أسبوعية في الفقه الإسلامي',
            contact_info: '777345678'
        },
        {
            title: 'ندوة حول الأخلاق الإسلامية',
            lecturer_name: 'الدكتور يحيى الشامي',
            type: 'ندوة',
            province: 'عدن',
            location: 'المركز الثقافي الإسلامي',
            day_of_week: 'السبت',
            time: '16:00',
            description: 'ندوة شهرية حول الأخلاق والقيم الإسلامية',
            contact_info: '777456789'
        },
        {
            title: 'دورة في تجويد القرآن',
            lecturer_name: 'الشيخ أحمد المقطري',
            type: 'دورة',
            province: 'الحديدة',
            location: 'مسجد النور',
            day_of_week: 'الخميس',
            time: '17:30',
            description: 'دورة تدريبية في أحكام التجويد',
            contact_info: '777567890'
        },
        {
            title: 'محاضرة في العقيدة الإسلامية',
            lecturer_name: 'الشيخ محمد الوادعي',
            type: 'محاضرة',
            province: 'صعدة',
            location: 'مسجد الهدى',
            day_of_week: 'الاثنين',
            time: '20:30',
            description: 'محاضرات في أصول العقيدة الإسلامية',
            contact_info: '777678901'
        },
        {
            title: 'دروس في الحديث الشريف',
            lecturer_name: 'الدكتور عبدالرحمن الإرياني',
            type: 'درس',
            province: 'إب',
            location: 'جامع الفاروق',
            day_of_week: 'الأحد',
            time: '18:00',
            description: 'دروس أسبوعية في شرح الأحاديث النبوية',
            contact_info: '777789012'
        },
        {
            title: 'ندوة حول التربية الإسلامية',
            lecturer_name: 'الدكتورة فاطمة الزهراني',
            type: 'ندوة',
            province: 'ذمار',
            location: 'المركز النسائي الإسلامي',
            day_of_week: 'الخميس',
            time: '15:00',
            description: 'ندوة نسائية حول التربية الإسلامية للأطفال',
            contact_info: '777890123'
        },
        {
            title: 'دروس في اللغة العربية',
            lecturer_name: 'الأستاذ علي الشرعبي',
            type: 'درس',
            province: 'حضرموت',
            location: 'مسجد الرحمن',
            day_of_week: 'الثلاثاء',
            time: '19:30',
            description: 'دروس في قواعد اللغة العربية وآدابها',
            contact_info: '777901234'
        },
        {
            title: 'محاضرة في التاريخ الإسلامي',
            lecturer_name: 'الدكتور أحمد باذيب',
            type: 'محاضرة',
            province: 'حضرموت',
            location: 'جامع المكلا الكبير',
            day_of_week: 'الجمعة',
            time: '16:30',
            description: 'محاضرات في التاريخ الإسلامي والحضارة',
            contact_info: '777012345'
        }
    ];

    for (const lecture of initialLectures) {
        try {
            await pool.execute(
                `INSERT INTO lectures (title, lecturer_name, type, province, location, day_of_week, time, description, contact_info)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                [
                    lecture.title,
                    lecture.lecturer_name,
                    lecture.type,
                    lecture.province,
                    lecture.location,
                    lecture.day_of_week,
                    lecture.time,
                    lecture.description,
                    lecture.contact_info
                ]
            );
        } catch (error) {
            console.error(`خطأ في إضافة المحاضرة: ${lecture.title}`, error);
        }
    }

    console.log('✅ تم إضافة البيانات الأولية للمحاضرات');

    // عرض إحصائيات
    const [countResult] = await pool.execute('SELECT COUNT(*) as count FROM lectures');
    const [provinceResult] = await pool.execute('SELECT province, COUNT(*) as count FROM lectures GROUP BY province');
    const [typeResult] = await pool.execute('SELECT type, COUNT(*) as count FROM lectures GROUP BY type');

    console.log('\n📊 إحصائيات المحاضرات:');
    console.log(`📚 إجمالي المحاضرات: ${countResult[0].count}`);
    console.log('\n🏛️ توزيع المحافظات:');
    provinceResult.forEach(row => {
        console.log(`   ${row.province}: ${row.count} محاضرة`);
    });
    console.log('\n📝 توزيع الأنواع:');
    typeResult.forEach(row => {
        console.log(`   ${row.type}: ${row.count}`);
    });
}

// تشغيل الدالة إذا تم استدعاء الملف مباشرة
if (require.main === module) {
    createLecturesTable().then(() => {
        console.log('🎉 انتهت عملية إنشاء جدول المحاضرات');
        process.exit(0);
    }).catch(error => {
        console.error('❌ فشل في إنشاء جدول المحاضرات:', error);
        process.exit(1);
    });
}

module.exports = { createLecturesTable };
