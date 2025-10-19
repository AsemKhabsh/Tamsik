/**
 * نظام الإشعارات الموحد (Toast Notifications)
 * يوفر طريقة سهلة لعرض رسائل النجاح والخطأ والتحذير والمعلومات
 */

class ToastNotification {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // إنشاء حاوية الإشعارات إذا لم تكن موجودة
        if (!document.getElementById('toast-container')) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('toast-container');
        }
    }

    /**
     * عرض إشعار
     * @param {string} message - نص الرسالة
     * @param {string} type - نوع الإشعار (success, error, warning, info)
     * @param {number} duration - مدة العرض بالميلي ثانية (افتراضي: 5000)
     */
    show(message, type = 'info', duration = 5000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        // أيقونة حسب النوع
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const icon = icons[type] || icons.info;
        
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas ${icon}"></i>
            </div>
            <div class="toast-content">
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" aria-label="إغلاق">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // إضافة الإشعار للحاوية
        this.container.appendChild(toast);
        
        // تأثير الظهور
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // زر الإغلاق
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            this.hide(toast);
        });
        
        // الإخفاء التلقائي
        if (duration > 0) {
            setTimeout(() => {
                this.hide(toast);
            }, duration);
        }
        
        return toast;
    }

    /**
     * إخفاء إشعار
     * @param {HTMLElement} toast - عنصر الإشعار
     */
    hide(toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }

    /**
     * إشعار نجاح
     */
    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    /**
     * إشعار خطأ
     */
    error(message, duration = 7000) {
        return this.show(message, 'error', duration);
    }

    /**
     * إشعار تحذير
     */
    warning(message, duration = 6000) {
        return this.show(message, 'warning', duration);
    }

    /**
     * إشعار معلومات
     */
    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }

    /**
     * مسح جميع الإشعارات
     */
    clearAll() {
        const toasts = this.container.querySelectorAll('.toast');
        toasts.forEach(toast => this.hide(toast));
    }
}

// إنشاء نسخة عامة
const toast = new ToastNotification();

// دوال مساعدة عامة
window.showToast = (message, type = 'info', duration = 5000) => {
    return toast.show(message, type, duration);
};

window.showSuccess = (message, duration = 5000) => {
    return toast.success(message, duration);
};

window.showError = (message, duration = 7000) => {
    return toast.error(message, duration);
};

window.showWarning = (message, duration = 6000) => {
    return toast.warning(message, duration);
};

window.showInfo = (message, duration = 5000) => {
    return toast.info(message, duration);
};

// التعامل مع رسائل Laravel Session
document.addEventListener('DOMContentLoaded', function() {
    // رسائل النجاح
    const successMessages = document.querySelectorAll('[data-toast-success]');
    successMessages.forEach(el => {
        toast.success(el.getAttribute('data-toast-success'));
        el.remove();
    });
    
    // رسائل الخطأ
    const errorMessages = document.querySelectorAll('[data-toast-error]');
    errorMessages.forEach(el => {
        toast.error(el.getAttribute('data-toast-error'));
        el.remove();
    });
    
    // رسائل التحذير
    const warningMessages = document.querySelectorAll('[data-toast-warning]');
    warningMessages.forEach(el => {
        toast.warning(el.getAttribute('data-toast-warning'));
        el.remove();
    });
    
    // رسائل المعلومات
    const infoMessages = document.querySelectorAll('[data-toast-info]');
    infoMessages.forEach(el => {
        toast.info(el.getAttribute('data-toast-info'));
        el.remove();
    });
});

// تصدير للاستخدام في modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ToastNotification;
}

