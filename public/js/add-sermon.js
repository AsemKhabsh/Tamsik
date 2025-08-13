// ملف JavaScript لإدارة إضافة الخطب الجديدة

document.addEventListener('DOMContentLoaded', function() {
    // التحقق من صلاحية الوصول للصفحة
    if (!checkSermonAddPermission()) {
        return; // إيقاف تحميل باقي الكود إذا لم يكن لديه صلاحية
    }

    // عناصر النموذج
    const sermonForm = document.getElementById('add-sermon-form');
    const excerptField = document.getElementById('sermon-excerpt');
    const excerptCount = document.getElementById('excerpt-count');
    const submissionMessage = document.getElementById('submission-message');
    const addAnotherBtn = document.getElementById('add-another');
    
    // تحديث عداد الأحرف في ملخص الخطبة
    if (excerptField && excerptCount) {
        excerptField.addEventListener('input', function() {
            excerptCount.textContent = this.value.length;
        });
    }
    
    // معالجة تقديم النموذج
    if (sermonForm) {
        sermonForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // جمع بيانات الخطبة
            const sermonData = {
                id: Date.now(), // استخدام الطابع الزمني كمعرف فريد
                title: document.getElementById('sermon-title').value,
                preacher: document.getElementById('preacher-name').value,
                date: formatDate(document.getElementById('sermon-date').value),
                category: document.getElementById('sermon-category').value,
                excerpt: document.getElementById('sermon-excerpt').value,
                content: document.getElementById('sermon-content').value,
                references: document.getElementById('sermon-references').value,
                tags: document.getElementById('sermon-tags').value.split(',').map(tag => tag.trim()).filter(tag => tag),
                views: 0,
                downloads: 0,
                dateAdded: new Date().toISOString(),
                featured: false
            };
            
            // تخزين الخطبة في التخزين المحلي
            saveSermon(sermonData);
            
            // عرض رسالة النجاح
            sermonForm.style.display = 'none';
            submissionMessage.style.display = 'block';
        });
    }
    
    // زر إضافة خطبة أخرى
    if (addAnotherBtn) {
        addAnotherBtn.addEventListener('click', function() {
            sermonForm.reset();
            sermonForm.style.display = 'block';
            submissionMessage.style.display = 'none';
        });
    }
    
    // تنسيق التاريخ بالصيغة العربية
    function formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        
        // تحويل التاريخ إلى صيغة عربية (مثال: 15 رمضان 1444)
        // هذا مجرد مثال بسيط، يمكن استخدام مكتبات متخصصة لتحويل التاريخ الميلادي إلى هجري
        return date.toLocaleDateString('ar-SA', options);
    }
    
    // حفظ الخطبة في التخزين المحلي
    function saveSermon(sermon) {
        // الحصول على الخطب المخزنة سابقاً
        let sermons = JSON.parse(localStorage.getItem('sermons')) || [];
        
        // إضافة الخطبة الجديدة
        sermons.push(sermon);
        
        // حفظ القائمة المحدثة
        localStorage.setItem('sermons', JSON.stringify(sermons));
    }
});

// دالة للتحقق من صلاحية إضافة الخطبة
function checkSermonAddPermission() {
    // التحقق من وجود نظام الحماية
    if (!window.authProtection) {
        console.error('نظام الحماية غير متوفر');
        showAccessDeniedPage('نظام الحماية غير متوفر');
        return false;
    }

    // التحقق من تسجيل الدخول
    if (!window.authProtection.isLoggedIn()) {
        console.log('المستخدم غير مسجل دخول');
        showAccessDeniedPage('يجب تسجيل الدخول أولاً للوصول إلى هذه الصفحة');
        return false;
    }

    // الحصول على بيانات المستخدم الحالي
    const currentUser = window.authProtection.getCurrentUser();
    if (!currentUser) {
        console.log('لا يمكن الحصول على بيانات المستخدم');
        showAccessDeniedPage('خطأ في الحصول على بيانات المستخدم');
        return false;
    }

    // التحقق من الدور المسموح (مشرف المنصة، عالم، خطيب)
    const allowedRoles = ['admin', 'scholar', 'member'];
    if (!allowedRoles.includes(currentUser.role)) {
        console.log(`المستخدم ليس لديه صلاحية. الدور الحالي: ${currentUser.role}`);
        showAccessDeniedPage('ليس لديك صلاحية لإضافة خطبة. يُسمح فقط للخطباء والعلماء ومشرفي المنصة بإضافة الخطب.');
        return false;
    }

    console.log('المستخدم لديه صلاحية إضافة الخطب');
    return true;
}

// دالة لعرض صفحة منع الوصول
function showAccessDeniedPage(message) {
    const container = document.querySelector('.add-sermon-form-container');
    if (container) {
        container.innerHTML = `
            <div class="form-card">
                <div class="access-denied">
                    <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                    <h2 style="color: #e74c3c; margin-bottom: 1rem;">غير مسموح بالوصول</h2>
                    <p style="margin-bottom: 2rem; color: #666;">${message}</p>
                    <div class="access-denied-actions">
                        <a href="sermons.html" class="btn btn-primary">العودة إلى الخطب الجاهزة</a>
                        <a href="login.html" class="btn btn-outline">تسجيل الدخول</a>
                    </div>
                </div>
            </div>
        `;
    }
}