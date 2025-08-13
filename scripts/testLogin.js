/**
 * اختبار مباشر لـ API تسجيل الدخول
 */

async function testLogin() {
    const BASE_URL = 'http://localhost:3000';
    
    console.log('🔍 اختبار API تسجيل الدخول...');
    
    try {
        const loginData = {
            email: 'admin@tamsik.com',
            password: 'admin123'
        };
        
        console.log('📤 إرسال طلب تسجيل الدخول...');
        console.log('البيانات:', JSON.stringify(loginData, null, 2));
        
        const response = await fetch(`${BASE_URL}/api/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(loginData)
        });
        
        console.log(`📥 استجابة الخادم: ${response.status} ${response.statusText}`);
        
        const responseText = await response.text();
        console.log('محتوى الاستجابة:', responseText);
        
        try {
            const data = JSON.parse(responseText);
            console.log('البيانات المحللة:', JSON.stringify(data, null, 2));
            
            if (response.ok && data.success) {
                console.log('✅ تسجيل الدخول نجح!');
                if (data.data && data.data.token) {
                    console.log(`🔑 رمز المصادقة: ${data.data.token.substring(0, 20)}...`);
                }
            } else {
                console.log('❌ تسجيل الدخول فشل:', data.message);
            }
        } catch (parseError) {
            console.log('❌ خطأ في تحليل JSON:', parseError.message);
        }
        
    } catch (error) {
        console.error('❌ خطأ في الشبكة:', error.message);
    }
}

// اختبار إضافي للتحقق من endpoint
async function testEndpoint() {
    const BASE_URL = 'http://localhost:3000';
    
    console.log('\n🔍 اختبار وجود endpoint...');
    
    try {
        const response = await fetch(`${BASE_URL}/api/auth/login`, {
            method: 'GET'
        });
        
        console.log(`📥 استجابة GET: ${response.status} ${response.statusText}`);
        
        if (response.status === 404) {
            console.log('❌ endpoint غير موجود');
        } else if (response.status === 405) {
            console.log('✅ endpoint موجود لكن لا يدعم GET (هذا طبيعي)');
        } else {
            console.log('✅ endpoint موجود');
        }
        
    } catch (error) {
        console.error('❌ خطأ في فحص endpoint:', error.message);
    }
}

// اختبار الخادم
async function testServer() {
    const BASE_URL = 'http://localhost:3000';
    
    console.log('\n🔍 اختبار حالة الخادم...');
    
    try {
        const response = await fetch(`${BASE_URL}/`);
        console.log(`📥 استجابة الخادم: ${response.status} ${response.statusText}`);
        
        if (response.ok) {
            console.log('✅ الخادم يعمل بشكل صحيح');
        } else {
            console.log('❌ مشكلة في الخادم');
        }
        
    } catch (error) {
        console.error('❌ الخادم غير متاح:', error.message);
    }
}

async function runTests() {
    await testServer();
    await testEndpoint();
    await testLogin();
}

runTests();
