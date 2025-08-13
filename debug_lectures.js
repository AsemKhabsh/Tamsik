// ملف تشخيص لصفحة المحاضرات
// يمكن تشغيل هذا الكود في وحدة التحكم (Console) في المتصفح

console.log('🔧 بدء تشخيص صفحة المحاضرات...');

// 1. التحقق من وجود العناصر المطلوبة
function checkElements() {
    console.log('\n📋 فحص العناصر:');
    
    const elements = {
        'add-lecture-btn': document.getElementById('add-lecture-btn'),
        'lecture-add-info': document.getElementById('lecture-add-info'),
        'add-info-message': document.getElementById('add-info-message'),
        'info-actions': document.getElementById('info-actions')
    };
    
    Object.entries(elements).forEach(([name, element]) => {
        if (element) {
            console.log(`✅ ${name}: موجود`);
            if (element.style.display) {
                console.log(`   📱 العرض: ${element.style.display}`);
            }
        } else {
            console.log(`❌ ${name}: غير موجود`);
        }
    });
}

// 2. التحقق من حالة المستخدم
function checkUserState() {
    console.log('\n👤 فحص حالة المستخدم:');
    
    // التحقق من نظام الحماية
    if (window.authProtection) {
        console.log('✅ نظام الحماية متوفر');
        if (window.authProtection.isLoggedIn()) {
            const user = window.authProtection.getCurrentUser();
            console.log('✅ مستخدم مسجل دخول:', user);
        } else {
            console.log('❌ لا يوجد مستخدم مسجل دخول في نظام الحماية');
        }
    } else {
        console.log('❌ نظام الحماية غير متوفر');
    }
    
    // التحقق من التخزين المحلي
    const userData = localStorage.getItem('currentUser');
    if (userData) {
        try {
            const user = JSON.parse(userData);
            console.log('✅ مستخدم في التخزين المحلي:', user);
        } catch (error) {
            console.log('❌ خطأ في قراءة بيانات المستخدم:', error);
        }
    } else {
        console.log('❌ لا يوجد مستخدم في التخزين المحلي');
    }
    
    // التحقق من المتغير العام
    if (window.currentUser) {
        console.log('✅ المستخدم الحالي (متغير عام):', window.currentUser);
    } else {
        console.log('❌ لا يوجد مستخدم في المتغير العام');
    }
}

// 3. إنشاء مستخدم تجريبي
function createTestUser(role = 'member') {
    console.log(`\n🧪 إنشاء مستخدم تجريبي بدور: ${role}`);
    
    const testUser = {
        id: 999,
        name: `مستخدم تجريبي (${role})`,
        role: role,
        email: 'test@example.com'
    };
    
    // حفظ في التخزين المحلي
    localStorage.setItem('currentUser', JSON.stringify(testUser));
    
    // تحديث نظام الحماية إن وجد
    if (window.authProtection) {
        window.authProtection.currentUser = testUser;
    }
    
    console.log('✅ تم إنشاء المستخدم التجريبي:', testUser);
    
    // إعادة تشغيل فحص الصلاحيات
    if (typeof manageLectureAddPermissions === 'function') {
        manageLectureAddPermissions();
    } else if (typeof checkUserLogin === 'function') {
        checkUserLogin();
    }
    
    return testUser;
}

// 4. إزالة المستخدم التجريبي
function removeTestUser() {
    console.log('\n🗑️ إزالة المستخدم التجريبي...');
    
    localStorage.removeItem('currentUser');
    
    if (window.authProtection) {
        window.authProtection.currentUser = null;
    }
    
    console.log('✅ تم إزالة المستخدم التجريبي');
    
    // إعادة تشغيل فحص الصلاحيات
    if (typeof manageLectureAddPermissions === 'function') {
        manageLectureAddPermissions();
    } else if (typeof checkUserLogin === 'function') {
        checkUserLogin();
    }
}

// 5. إظهار الزر يدوياً
function forceShowButton() {
    console.log('\n🔧 إظهار الزر يدوياً...');
    
    const btn = document.getElementById('add-lecture-btn');
    if (btn) {
        btn.style.display = 'block';
        btn.style.backgroundColor = '#28a745';
        btn.innerHTML = '<i class="fas fa-plus"></i> زر مُظهر يدوياً';
        console.log('✅ تم إظهار الزر يدوياً');
    } else {
        console.log('❌ لم يتم العثور على الزر');
    }
}

// 6. تشخيص شامل
function fullDiagnosis() {
    console.log('🔍 تشخيص شامل لصفحة المحاضرات');
    console.log('=====================================');
    
    checkElements();
    checkUserState();
    
    console.log('\n🛠️ الأوامر المتاحة:');
    console.log('- createTestUser("admin") - إنشاء مشرف تجريبي');
    console.log('- createTestUser("scholar") - إنشاء عالم تجريبي');
    console.log('- createTestUser("member") - إنشاء خطيب تجريبي');
    console.log('- removeTestUser() - إزالة المستخدم التجريبي');
    console.log('- forceShowButton() - إظهار الزر يدوياً');
    console.log('- checkElements() - فحص العناصر');
    console.log('- checkUserState() - فحص حالة المستخدم');
}

// تصدير الدوال للاستخدام العام
window.lectureDebug = {
    checkElements,
    checkUserState,
    createTestUser,
    removeTestUser,
    forceShowButton,
    fullDiagnosis
};

// تشغيل التشخيص الشامل تلقائياً
fullDiagnosis();

console.log('\n✅ تم تحميل أدوات التشخيص. استخدم lectureDebug.fullDiagnosis() لإعادة التشخيص');
