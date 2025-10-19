/**
 * Dark Mode Toggle
 * 
 * نظام تبديل الوضع الليلي/النهاري
 * يحفظ التفضيل في localStorage
 */

class DarkModeToggle {
    constructor() {
        this.darkModeKey = 'tamsik_dark_mode';
        this.init();
    }

    /**
     * تهيئة النظام
     */
    init() {
        // تحميل الوضع المحفوظ
        this.loadSavedMode();
        
        // إنشاء زر التبديل
        this.createToggleButton();
        
        // الاستماع لتغييرات النظام
        this.watchSystemPreference();
    }

    /**
     * تحميل الوضع المحفوظ
     */
    loadSavedMode() {
        const savedMode = localStorage.getItem(this.darkModeKey);
        
        if (savedMode === 'enabled') {
            document.body.classList.add('dark-mode');
        } else if (savedMode === null) {
            // إذا لم يكن هناك تفضيل محفوظ، استخدم تفضيل النظام
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.body.classList.add('dark-mode');
            }
        }
    }

    /**
     * إنشاء زر التبديل
     */
    createToggleButton() {
        // إنشاء الزر
        const button = document.createElement('button');
        button.className = 'dark-mode-toggle';
        button.setAttribute('aria-label', 'تبديل الوضع الليلي');
        button.setAttribute('title', 'تبديل الوضع الليلي/النهاري');
        
        // إضافة الأيقونة
        this.updateButtonIcon(button);
        
        // إضافة حدث النقر
        button.addEventListener('click', () => this.toggle(button));
        
        // إضافة الزر إلى الصفحة
        document.body.appendChild(button);
    }

    /**
     * تحديث أيقونة الزر
     */
    updateButtonIcon(button) {
        const isDark = document.body.classList.contains('dark-mode');
        button.innerHTML = isDark 
            ? '<i class="fas fa-sun"></i>' 
            : '<i class="fas fa-moon"></i>';
    }

    /**
     * تبديل الوضع
     */
    toggle(button) {
        document.body.classList.toggle('dark-mode');
        const isDark = document.body.classList.contains('dark-mode');
        
        // حفظ التفضيل
        localStorage.setItem(this.darkModeKey, isDark ? 'enabled' : 'disabled');
        
        // تحديث الأيقونة
        this.updateButtonIcon(button);
        
        // إظهار إشعار
        this.showNotification(isDark);
    }

    /**
     * إظهار إشعار التبديل
     */
    showNotification(isDark) {
        if (typeof showToast === 'function') {
            showToast(
                isDark ? 'تم تفعيل الوضع الليلي' : 'تم تفعيل الوضع النهاري',
                'info'
            );
        }
    }

    /**
     * مراقبة تفضيلات النظام
     */
    watchSystemPreference() {
        if (window.matchMedia) {
            const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            darkModeQuery.addEventListener('change', (e) => {
                // فقط إذا لم يكن هناك تفضيل محفوظ
                if (localStorage.getItem(this.darkModeKey) === null) {
                    if (e.matches) {
                        document.body.classList.add('dark-mode');
                    } else {
                        document.body.classList.remove('dark-mode');
                    }
                }
            });
        }
    }
}

// تهيئة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    new DarkModeToggle();
});

