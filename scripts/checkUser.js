/**
 * فحص بيانات المستخدم في قاعدة البيانات
 */

const sqlite3 = require('sqlite3').verbose();
const bcrypt = require('bcryptjs');
const path = require('path');

const DB_PATH = path.join(__dirname, '..', 'database.sqlite');

function checkUser() {
    const db = new sqlite3.Database(DB_PATH);
    
    console.log('🔍 فحص بيانات المستخدمين في قاعدة البيانات...');
    
    db.all('SELECT * FROM users', [], (err, rows) => {
        if (err) {
            console.error('❌ خطأ في قراءة البيانات:', err.message);
            db.close();
            return;
        }
        
        console.log(`✅ تم العثور على ${rows.length} مستخدم(ين):`);
        
        rows.forEach((user, index) => {
            console.log(`\n👤 المستخدم ${index + 1}:`);
            console.log(`   المعرف: ${user.id}`);
            console.log(`   الاسم: ${user.name}`);
            console.log(`   البريد الإلكتروني: ${user.email}`);
            console.log(`   الدور: ${user.role}`);
            console.log(`   كلمة المرور المشفرة: ${user.password.substring(0, 20)}...`);
            console.log(`   تاريخ الإنشاء: ${user.created_at}`);
        });
        
        // اختبار كلمة المرور
        if (rows.length > 0) {
            const user = rows[0];
            console.log('\n🔐 اختبار كلمة المرور...');
            
            bcrypt.compare('admin123', user.password, (err, result) => {
                if (err) {
                    console.error('❌ خطأ في مقارنة كلمة المرور:', err.message);
                } else if (result) {
                    console.log('✅ كلمة المرور صحيحة!');
                } else {
                    console.log('❌ كلمة المرور غير صحيحة');
                    
                    // محاولة إنشاء كلمة مرور جديدة
                    console.log('🔧 إنشاء كلمة مرور جديدة...');
                    bcrypt.hash('admin123', 10, (err, hash) => {
                        if (err) {
                            console.error('❌ خطأ في تشفير كلمة المرور:', err.message);
                        } else {
                            console.log('✅ كلمة المرور الجديدة:', hash.substring(0, 20) + '...');
                            
                            // تحديث كلمة المرور في قاعدة البيانات
                            db.run(
                                'UPDATE users SET password = ? WHERE id = ?',
                                [hash, user.id],
                                (err) => {
                                    if (err) {
                                        console.error('❌ خطأ في تحديث كلمة المرور:', err.message);
                                    } else {
                                        console.log('✅ تم تحديث كلمة المرور بنجاح');
                                    }
                                    db.close();
                                }
                            );
                        }
                    });
                }
            });
        } else {
            db.close();
        }
    });
}

checkUser();
