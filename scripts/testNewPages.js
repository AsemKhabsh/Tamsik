/**
 * اختبار الصفحات الجديدة (لوحة الإدارة والملف الشخصي)
 */

async function testNewPages() {
    const BASE_URL = 'http://localhost:3000';
    
    console.log('🚀 بدء اختبار الصفحات الجديدة...');
    console.log('=' .repeat(50));
    
    let passedTests = 0;
    let failedTests = 0;
    
    // اختبار صفحة لوحة الإدارة
    console.log('\n🔍 اختبار صفحة لوحة الإدارة...');
    try {
        const response = await fetch(`${BASE_URL}/admin.html`);
        if (response.ok) {
            const content = await response.text();
            if (content.includes('لوحة الإدارة') && content.includes('إدارة المستخدمين')) {
                console.log('✅ صفحة لوحة الإدارة تعمل بشكل صحيح');
                passedTests++;
            } else {
                console.log('⚠️  صفحة لوحة الإدارة تحتوي على مشاكل في المحتوى');
                failedTests++;
            }
        } else {
            console.log(`❌ صفحة لوحة الإدارة غير متاحة: ${response.status}`);
            failedTests++;
        }
    } catch (error) {
        console.log(`❌ خطأ في تحميل صفحة لوحة الإدارة: ${error.message}`);
        failedTests++;
    }
    
    // اختبار صفحة الملف الشخصي
    console.log('\n🔍 اختبار صفحة الملف الشخصي...');
    try {
        const response = await fetch(`${BASE_URL}/profile.html`);
        if (response.ok) {
            const content = await response.text();
            if (content.includes('الملف الشخصي') && content.includes('تحرير المعلومات الشخصية')) {
                console.log('✅ صفحة الملف الشخصي تعمل بشكل صحيح');
                passedTests++;
            } else {
                console.log('⚠️  صفحة الملف الشخصي تحتوي على مشاكل في المحتوى');
                failedTests++;
            }
        } else {
            console.log(`❌ صفحة الملف الشخصي غير متاحة: ${response.status}`);
            failedTests++;
        }
    } catch (error) {
        console.log(`❌ خطأ في تحميل صفحة الملف الشخصي: ${error.message}`);
        failedTests++;
    }
    
    // اختبار ملفات CSS
    console.log('\n🔍 اختبار ملفات CSS...');
    const cssFiles = ['/css/admin.css', '/css/profile.css'];
    
    for (const cssFile of cssFiles) {
        try {
            const response = await fetch(`${BASE_URL}${cssFile}`);
            if (response.ok) {
                console.log(`✅ ملف ${cssFile} متاح`);
                passedTests++;
            } else {
                console.log(`❌ ملف ${cssFile} غير متاح: ${response.status}`);
                failedTests++;
            }
        } catch (error) {
            console.log(`❌ خطأ في تحميل ${cssFile}: ${error.message}`);
            failedTests++;
        }
    }
    
    // اختبار ملفات JavaScript
    console.log('\n🔍 اختبار ملفات JavaScript...');
    const jsFiles = ['/js/admin.js', '/js/profile.js'];
    
    for (const jsFile of jsFiles) {
        try {
            const response = await fetch(`${BASE_URL}${jsFile}`);
            if (response.ok) {
                console.log(`✅ ملف ${jsFile} متاح`);
                passedTests++;
            } else {
                console.log(`❌ ملف ${jsFile} غير متاح: ${response.status}`);
                failedTests++;
            }
        } catch (error) {
            console.log(`❌ خطأ في تحميل ${jsFile}: ${error.message}`);
            failedTests++;
        }
    }
    
    // اختبار API endpoints للمدير
    console.log('\n🔍 اختبار API endpoints للمدير...');
    
    // محاولة تسجيل الدخول كمدير
    try {
        const loginResponse = await fetch(`${BASE_URL}/api/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: 'admin@tamsik.com',
                password: 'admin123'
            })
        });
        
        if (loginResponse.ok) {
            const loginData = await loginResponse.json();
            const token = loginData.data?.token;
            
            if (token) {
                console.log('✅ تسجيل دخول المدير نجح');
                passedTests++;
                
                // اختبار API المستخدمين
                try {
                    const usersResponse = await fetch(`${BASE_URL}/api/users`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    });
                    
                    if (usersResponse.ok) {
                        console.log('✅ API المستخدمين يعمل للمدير');
                        passedTests++;
                    } else {
                        console.log(`❌ API المستخدمين فشل: ${usersResponse.status}`);
                        failedTests++;
                    }
                } catch (error) {
                    console.log(`❌ خطأ في API المستخدمين: ${error.message}`);
                    failedTests++;
                }
                
            } else {
                console.log('❌ لم يتم الحصول على رمز المصادقة');
                failedTests++;
            }
        } else {
            console.log(`❌ فشل تسجيل دخول المدير: ${loginResponse.status}`);
            failedTests++;
        }
    } catch (error) {
        console.log(`❌ خطأ في تسجيل دخول المدير: ${error.message}`);
        failedTests++;
    }
    
    // اختبار التنقل في الصفحة الرئيسية
    console.log('\n🔍 اختبار التنقل في الصفحة الرئيسية...');
    try {
        const response = await fetch(`${BASE_URL}/`);
        if (response.ok) {
            const content = await response.text();
            if (content.includes('admin.html') && content.includes('profile.html')) {
                console.log('✅ روابط التنقل الجديدة موجودة في الصفحة الرئيسية');
                passedTests++;
            } else {
                console.log('⚠️  روابط التنقل الجديدة مفقودة من الصفحة الرئيسية');
                failedTests++;
            }
        } else {
            console.log(`❌ فشل في تحميل الصفحة الرئيسية: ${response.status}`);
            failedTests++;
        }
    } catch (error) {
        console.log(`❌ خطأ في تحميل الصفحة الرئيسية: ${error.message}`);
        failedTests++;
    }
    
    // عرض النتائج النهائية
    console.log('\n📊 ملخص نتائج اختبار الصفحات الجديدة:');
    console.log('=' .repeat(50));
    console.log(`✅ اختبارات نجحت: ${passedTests}`);
    if (failedTests > 0) {
        console.log(`❌ اختبارات فشلت: ${failedTests}`);
    }
    
    const successRate = ((passedTests / (passedTests + failedTests)) * 100).toFixed(1);
    console.log(`🎯 معدل النجاح: ${successRate}%`);
    
    if (successRate >= 90) {
        console.log('🎉 الصفحات الجديدة جاهزة للاستخدام!');
    } else if (successRate >= 70) {
        console.log('⚠️  الصفحات الجديدة تحتاج بعض التحسينات');
    } else {
        console.log('🚨 الصفحات الجديدة تحتاج إصلاحات مهمة');
    }
    
    return { passedTests, failedTests, successRate };
}

// تشغيل الاختبار
if (require.main === module) {
    testNewPages().catch(error => {
        console.error('❌ خطأ عام في اختبار الصفحات الجديدة:', error.message);
        process.exit(1);
    });
}

module.exports = { testNewPages };
