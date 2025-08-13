/**
 * نظام حماية شامل للصفحات الأمامية
 * يمنع الوصول للصفحات بدون تسجيل دخول
 */

// معالجة الأخطاء العامة في هذا الملف
(function() {
    'use strict';

    // التأكد من وجود console
    if (typeof console === 'undefined') {
        window.console = {
            log: function() {},
            error: function() {},
            warn: function() {},
            info: function() {}
        };
    }

// إعدادات الحماية
const PROTECTION_CONFIG = {
    // الصفحات التي تتطلب تسجيل دخول
    protectedPages: [
        'add_sermon.html',
        'prepare_sermon.html',
        'admin.html',
        'profile.html',
        'dashboard.html'
    ],
    
    // الصفحات العامة التي لا تحتاج تسجيل دخول
    publicPages: [
        'index.html',
        'login.html',
        'register.html',
        'scholars.html',
        'sermons.html',
        'lectures.html',
        'thinkers.html',
        'article-details.html',
        'sermon_details.html'
    ],
    
    // صفحة تسجيل الدخول
    loginPage: 'login.html',
    
    // الصفحة الرئيسية
    homePage: 'index.html'
};

// فئات المستخدمين والصلاحيات
const USER_ROLES = {
    admin: {
        level: 4,
        name: 'مشرف المنصة',
        permissions: ['read', 'write', 'delete', 'manage_users', 'manage_content']
    },
    scholar: {
        level: 3,
        name: 'عالم',
        permissions: ['read', 'write', 'answer_fatwas', 'add_sermons']
    },
    member: {
        level: 2,
        name: 'خطيب',
        permissions: ['read', 'write_limited', 'add_sermons']
    },
    guest: {
        level: 1,
        name: 'زائر',
        permissions: ['read']
    }
};

// دالة للحصول على المستخدم الحالي
function getCurrentUser() {
    try {
        const userData = localStorage.getItem('currentUser');
        if (!userData) return null;
        
        const user = JSON.parse(userData);
        
        // التحقق من انتهاء صلاحية الجلسة
        if (user.sessionExpiry && new Date() > new Date(user.sessionExpiry)) {
            logoutUser();
            return null;
        }
        
        return user;
    } catch (error) {
        console.error('خطأ في قراءة بيانات المستخدم:', error);
        return null;
    }
}

// دالة للتحقق من تسجيل الدخول
function isLoggedIn() {
    const user = getCurrentUser();
    return user !== null && user.isActive !== false;
}

// دالة للتحقق من الصلاحيات
function hasPermission(requiredPermission) {
    const user = getCurrentUser();
    if (!user) return false;
    
    const userRole = USER_ROLES[user.role] || USER_ROLES.guest;
    return userRole.permissions.includes(requiredPermission);
}

// دالة للتحقق من مستوى الصلاحية
function hasRoleLevel(requiredLevel) {
    const user = getCurrentUser();
    if (!user) return false;
    
    const userRole = USER_ROLES[user.role] || USER_ROLES.guest;
    return userRole.level >= requiredLevel;
}

// دالة للحصول على اسم الصفحة الحالية
function getCurrentPageName() {
    const path = window.location.pathname;
    const pageName = path.split('/').pop() || 'index.html';
    return pageName;
}

// دالة للتحقق من أن الصفحة محمية
function isProtectedPage(pageName = null) {
    const currentPage = pageName || getCurrentPageName();
    return PROTECTION_CONFIG.protectedPages.includes(currentPage);
}

// دالة لإعادة التوجيه إلى صفحة تسجيل الدخول
function redirectToLogin() {
    const currentPage = getCurrentPageName();
    const returnUrl = encodeURIComponent(window.location.href);
    
    // حفظ الصفحة المطلوبة للعودة إليها بعد تسجيل الدخول
    sessionStorage.setItem('returnUrl', window.location.href);
    
    window.location.href = `${PROTECTION_CONFIG.loginPage}?return=${returnUrl}`;
}

// دالة لإعادة التوجيه إلى الصفحة الرئيسية
function redirectToHome() {
    window.location.href = PROTECTION_CONFIG.homePage;
}

// دالة لعرض رسالة عدم وجود صلاحية
function showAccessDeniedMessage() {
    const message = `
        <div class="access-denied-overlay">
            <div class="access-denied-modal">
                <div class="access-denied-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h3>ليس لديك صلاحية للوصول</h3>
                <p>هذه الصفحة تتطلب صلاحيات خاصة للوصول إليها.</p>
                <div class="access-denied-actions">
                    <button onclick="redirectToHome()" class="btn btn-primary">العودة للرئيسية</button>
                    <button onclick="logoutAndRedirect()" class="btn btn-secondary">تسجيل دخول بحساب آخر</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', message);
}

// دالة لتسجيل الخروج وإعادة التوجيه
function logoutAndRedirect() {
    logoutUser();
    redirectToLogin();
}

// دالة لتسجيل الخروج
function logoutUser() {
    localStorage.removeItem('currentUser');
    localStorage.removeItem('authToken');
    sessionStorage.removeItem('returnUrl');
    
    // إشعار باقي علامات التبويب بتسجيل الخروج
    localStorage.setItem('logout-event', Date.now());
}

// دالة للتحقق من الحماية عند تحميل الصفحة
function checkPageProtection() {
    const currentPage = getCurrentPageName();
    const user = getCurrentUser();
    
    console.log(`🔒 فحص الحماية للصفحة: ${currentPage}`);
    
    // إذا كانت الصفحة محمية
    if (isProtectedPage(currentPage)) {
        if (!isLoggedIn()) {
            console.log('❌ المستخدم غير مسجل دخول، إعادة توجيه لتسجيل الدخول');
            redirectToLogin();
            return false;
        }
        
        // التحقق من الصلاحيات الخاصة لبعض الصفحات
        if (currentPage === 'admin.html' && !hasRoleLevel(4)) {
            console.log('❌ المستخدم ليس مديراً، منع الوصول');
            showAccessDeniedMessage();
            return false;
        }
        
        if (currentPage.includes('add_') && !hasPermission('write')) {
            console.log('❌ المستخدم ليس لديه صلاحية الكتابة');
            showAccessDeniedMessage();
            return false;
        }
    }
    
    // إذا كان المستخدم مسجل دخول ويحاول الوصول لصفحة تسجيل الدخول
    if (currentPage === PROTECTION_CONFIG.loginPage && isLoggedIn()) {
        console.log('✅ المستخدم مسجل دخول بالفعل، إعادة توجيه للرئيسية');
        redirectToHome();
        return false;
    }
    
    console.log('✅ تم السماح بالوصول للصفحة');
    return true;
}

// دالة لتحديث واجهة المستخدم حسب حالة تسجيل الدخول
function updateUIBasedOnAuth() {
    const user = getCurrentUser();
    const isAuthenticated = isLoggedIn();
    
    // إخفاء/إظهار عناصر الواجهة حسب حالة تسجيل الدخول
    const authElements = document.querySelectorAll('[data-auth-required]');
    const guestElements = document.querySelectorAll('[data-guest-only]');
    const adminElements = document.querySelectorAll('[data-admin-only]');
    const scholarElements = document.querySelectorAll('[data-scholar-only]');
    
    authElements.forEach(element => {
        element.style.display = isAuthenticated ? '' : 'none';
    });
    
    guestElements.forEach(element => {
        element.style.display = isAuthenticated ? 'none' : '';
    });
    
    adminElements.forEach(element => {
        element.style.display = hasRoleLevel(4) ? '' : 'none';
    });
    
    scholarElements.forEach(element => {
        element.style.display = hasRoleLevel(3) ? '' : 'none';
    });
    
    // تحديث معلومات المستخدم في الواجهة
    if (isAuthenticated && user) {
        const userNameElements = document.querySelectorAll('[data-user-name]');
        const userRoleElements = document.querySelectorAll('[data-user-role]');
        
        userNameElements.forEach(element => {
            element.textContent = user.name || user.username || 'مستخدم';
        });
        
        userRoleElements.forEach(element => {
            const roleInfo = USER_ROLES[user.role] || USER_ROLES.guest;
            element.textContent = roleInfo.name;
        });
    }
}

// مراقبة تسجيل الخروج في علامات تبويب أخرى
window.addEventListener('storage', function(e) {
    if (e.key === 'logout-event') {
        // تم تسجيل الخروج في علامة تبويب أخرى
        window.location.reload();
    }
});

// تشغيل فحص الحماية عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    try {
        // فحص الحماية
        if (!checkPageProtection()) {
            return; // إيقاف التحميل إذا لم يُسمح بالوصول
        }

        // تحديث الواجهة
        updateUIBasedOnAuth();

        // إضافة مستمعي الأحداث لأزرار تسجيل الخروج
        const logoutButtons = document.querySelectorAll('[data-logout]');
        logoutButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                logoutUser();
                redirectToLogin();
            });
        });
    } catch (error) {
        console.error('خطأ في تهيئة نظام الحماية:', error);
        // لا نوقف تحميل الصفحة في حالة خطأ في نظام الحماية
    }
});

// تصدير الوظائف للاستخدام العام
window.authProtection = {
    getCurrentUser,
    isLoggedIn,
    hasPermission,
    hasRoleLevel,
    redirectToLogin,
    redirectToHome,
    logoutUser,
    updateUIBasedOnAuth,
    checkPageProtection,
    USER_ROLES
};

})(); // إغلاق الدالة المجهولة
