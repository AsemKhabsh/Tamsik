/**
 * Service Worker for Tamsik Platform
 * PWA Support & Offline Functionality
 * Version: 1.0.0
 */

const CACHE_NAME = 'tamsik-v1.0.0';
const OFFLINE_URL = '/offline.html';

// الملفات التي سيتم تخزينها مؤقتاً
const STATIC_CACHE_URLS = [
    '/',
    '/offline.html',
    '/css/unified-theme.css',
    '/css/responsive.css',
    '/css/toast-notifications.css',
    '/css/performance-optimizations.css',
    '/js/toast-notifications.js',
    '/js/performance-optimizations.js',
    '/images/logo.png',
];

// تثبيت Service Worker
self.addEventListener('install', (event) => {
    console.log('[Service Worker] Installing...');
    
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[Service Worker] Caching static assets');
                return cache.addAll(STATIC_CACHE_URLS);
            })
            .then(() => {
                console.log('[Service Worker] Installed successfully');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('[Service Worker] Installation failed:', error);
            })
    );
});

// تفعيل Service Worker
self.addEventListener('activate', (event) => {
    console.log('[Service Worker] Activating...');
    
    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== CACHE_NAME) {
                            console.log('[Service Worker] Deleting old cache:', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('[Service Worker] Activated successfully');
                return self.clients.claim();
            })
    );
});

// استراتيجية التخزين المؤقت
self.addEventListener('fetch', (event) => {
    // تجاهل الطلبات غير GET
    if (event.request.method !== 'GET') {
        return;
    }

    // تجاهل طلبات Chrome Extensions
    if (event.request.url.startsWith('chrome-extension://')) {
        return;
    }

    event.respondWith(
        caches.match(event.request)
            .then((cachedResponse) => {
                // إذا وجدنا الملف في الـ cache، نعيده
                if (cachedResponse) {
                    return cachedResponse;
                }

                // إذا لم نجده، نحاول جلبه من الشبكة
                return fetch(event.request)
                    .then((response) => {
                        // تحقق من صحة الاستجابة
                        if (!response || response.status !== 200 || response.type === 'error') {
                            return response;
                        }

                        // نسخ الاستجابة
                        const responseToCache = response.clone();

                        // تخزين الاستجابة في الـ cache
                        caches.open(CACHE_NAME)
                            .then((cache) => {
                                // تخزين الملفات الثابتة فقط
                                if (shouldCache(event.request.url)) {
                                    cache.put(event.request, responseToCache);
                                }
                            });

                        return response;
                    })
                    .catch((error) => {
                        console.error('[Service Worker] Fetch failed:', error);
                        
                        // إذا فشل الطلب، نعرض صفحة offline
                        return caches.match(OFFLINE_URL);
                    });
            })
    );
});

// دالة للتحقق من الملفات التي يجب تخزينها
function shouldCache(url) {
    // تخزين الملفات الثابتة فقط (CSS, JS, Images)
    const staticExtensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.svg', '.woff', '.woff2'];
    return staticExtensions.some(ext => url.endsWith(ext));
}

// معالجة رسائل من الصفحة الرئيسية
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    
    if (event.data && event.data.type === 'CLEAR_CACHE') {
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    return caches.delete(cacheName);
                })
            );
        }).then(() => {
            event.ports[0].postMessage({ success: true });
        });
    }
});

// Background Sync للطلبات الفاشلة
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-sermons') {
        event.waitUntil(syncSermons());
    }
});

async function syncSermons() {
    try {
        // يمكن إضافة منطق المزامنة هنا
        console.log('[Service Worker] Syncing sermons...');
    } catch (error) {
        console.error('[Service Worker] Sync failed:', error);
    }
}

// Push Notifications
self.addEventListener('push', (event) => {
    const options = {
        body: event.data ? event.data.text() : 'إشعار جديد من تمسيك',
        icon: '/images/icon-192x192.png',
        badge: '/images/badge-72x72.png',
        vibrate: [200, 100, 200],
        dir: 'rtl',
        lang: 'ar',
        tag: 'tamsik-notification',
        requireInteraction: false,
        actions: [
            {
                action: 'open',
                title: 'فتح',
                icon: '/images/action-open.png'
            },
            {
                action: 'close',
                title: 'إغلاق',
                icon: '/images/action-close.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('تمسيك', options)
    );
});

// معالجة النقر على الإشعارات
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'open') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// معالجة إغلاق الإشعارات
self.addEventListener('notificationclose', (event) => {
    console.log('[Service Worker] Notification closed:', event.notification.tag);
});

console.log('[Service Worker] Loaded successfully');

