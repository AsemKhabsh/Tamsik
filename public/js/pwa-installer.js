/**
 * PWA Installer & Service Worker Registration
 * تسجيل Service Worker وتثبيت PWA
 * Version: 1.0.0
 */

(function() {
    'use strict';

    // ============================================
    // 1. تسجيل Service Worker
    // ============================================
    
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            registerServiceWorker();
        });
    }

    async function registerServiceWorker() {
        try {
            const registration = await navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            });

            console.log('[PWA] Service Worker registered successfully:', registration.scope);

            // التحقق من التحديثات
            registration.addEventListener('updatefound', () => {
                const newWorker = registration.installing;
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // يوجد تحديث جديد
                        showUpdateNotification();
                    }
                });
            });

        } catch (error) {
            console.error('[PWA] Service Worker registration failed:', error);
        }
    }

    function showUpdateNotification() {
        if (typeof showInfo === 'function') {
            showInfo('يوجد تحديث جديد! سيتم تطبيقه عند إعادة تحميل الصفحة.');
        }
    }

    // ============================================
    // 2. تثبيت PWA
    // ============================================
    
    let deferredPrompt;
    const installButton = document.getElementById('pwa-install-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // منع ظهور نافذة التثبيت التلقائية
        e.preventDefault();
        
        // حفظ الحدث للاستخدام لاحقاً
        deferredPrompt = e;
        
        // إظهار زر التثبيت
        if (installButton) {
            installButton.style.display = 'block';
        } else {
            // إنشاء زر تثبيت ديناميكي
            createInstallButton();
        }
    });

    function createInstallButton() {
        const button = document.createElement('button');
        button.id = 'pwa-install-btn';
        button.className = 'pwa-install-button';
        button.innerHTML = `
            <i class="fas fa-download"></i>
            <span>تثبيت التطبيق</span>
        `;
        button.onclick = installPWA;
        
        // إضافة الزر إلى الصفحة
        document.body.appendChild(button);
        
        // إضافة الأنماط
        addInstallButtonStyles();
    }

    function addInstallButtonStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .pwa-install-button {
                position: fixed;
                bottom: 20px;
                left: 20px;
                background: linear-gradient(135deg, #1d8a4e 0%, #15693a 100%);
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 50px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(29, 138, 78, 0.3);
                z-index: 1000;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s ease;
                animation: slideInUp 0.5s ease;
            }
            
            .pwa-install-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(29, 138, 78, 0.4);
            }
            
            .pwa-install-button i {
                font-size: 18px;
            }
            
            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            
            @media (max-width: 576px) {
                .pwa-install-button {
                    bottom: 10px;
                    left: 10px;
                    padding: 10px 20px;
                    font-size: 14px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    async function installPWA() {
        if (!deferredPrompt) {
            return;
        }

        // إظهار نافذة التثبيت
        deferredPrompt.prompt();

        // انتظار اختيار المستخدم
        const { outcome } = await deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            console.log('[PWA] User accepted the install prompt');
            
            if (typeof showSuccess === 'function') {
                showSuccess('تم تثبيت التطبيق بنجاح! 🎉');
            }
            
            // إخفاء زر التثبيت
            const button = document.getElementById('pwa-install-btn');
            if (button) {
                button.style.display = 'none';
            }
        } else {
            console.log('[PWA] User dismissed the install prompt');
        }

        // إعادة تعيين المتغير
        deferredPrompt = null;
    }

    // ============================================
    // 3. مراقبة حالة الاتصال
    // ============================================
    
    window.addEventListener('online', () => {
        console.log('[PWA] Connection restored');
        
        if (typeof showSuccess === 'function') {
            showSuccess('تم استعادة الاتصال بالإنترنت');
        }
        
        // إعادة تحميل البيانات
        reloadData();
    });

    window.addEventListener('offline', () => {
        console.log('[PWA] Connection lost');
        
        if (typeof showWarning === 'function') {
            showWarning('لا يوجد اتصال بالإنترنت. بعض الميزات قد لا تعمل.');
        }
    });

    function reloadData() {
        // يمكن إضافة منطق إعادة تحميل البيانات هنا
        console.log('[PWA] Reloading data...');
    }

    // ============================================
    // 4. Push Notifications
    // ============================================
    
    async function requestNotificationPermission() {
        if (!('Notification' in window)) {
            console.log('[PWA] This browser does not support notifications');
            return false;
        }

        if (Notification.permission === 'granted') {
            return true;
        }

        if (Notification.permission !== 'denied') {
            const permission = await Notification.requestPermission();
            return permission === 'granted';
        }

        return false;
    }

    async function subscribeToPushNotifications() {
        try {
            const registration = await navigator.serviceWorker.ready;
            
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    'YOUR_PUBLIC_VAPID_KEY_HERE' // يجب استبدالها بمفتاح VAPID الحقيقي
                )
            });

            console.log('[PWA] Push subscription:', subscription);

            // إرسال الاشتراك إلى الخادم
            await sendSubscriptionToServer(subscription);

            return subscription;
        } catch (error) {
            console.error('[PWA] Failed to subscribe to push notifications:', error);
            return null;
        }
    }

    async function sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/push-subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(subscription)
            });

            if (response.ok) {
                console.log('[PWA] Subscription sent to server successfully');
            }
        } catch (error) {
            console.error('[PWA] Failed to send subscription to server:', error);
        }
    }

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    // ============================================
    // 5. App Installed Event
    // ============================================
    
    window.addEventListener('appinstalled', () => {
        console.log('[PWA] App installed successfully');
        
        if (typeof showSuccess === 'function') {
            showSuccess('تم تثبيت التطبيق بنجاح! يمكنك الآن استخدامه من الشاشة الرئيسية.');
        }
        
        // إخفاء زر التثبيت
        const button = document.getElementById('pwa-install-btn');
        if (button) {
            button.style.display = 'none';
        }
        
        // حفظ حالة التثبيت
        localStorage.setItem('pwa-installed', 'true');
    });

    // ============================================
    // 6. تصدير الدوال للاستخدام العام
    // ============================================
    
    window.PWA = {
        install: installPWA,
        requestNotificationPermission,
        subscribeToPushNotifications,
        isInstalled: () => {
            return window.matchMedia('(display-mode: standalone)').matches ||
                   window.navigator.standalone ||
                   localStorage.getItem('pwa-installed') === 'true';
        }
    };

})();

