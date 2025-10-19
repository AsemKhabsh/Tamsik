/**
 * Performance Optimizations JavaScript
 * تحسينات الأداء والتجربة التفاعلية
 * Version: 1.0.0
 */

(function() {
    'use strict';

    // ============================================
    // 1. Lazy Loading للصور
    // ============================================
    
    class LazyImageLoader {
        constructor() {
            this.images = document.querySelectorAll('img[loading="lazy"]');
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                this.observeImages();
            } else {
                // Fallback للمتصفحات القديمة
                this.loadAllImages();
            }
        }

        observeImages() {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        this.loadImage(img);
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            this.images.forEach(img => imageObserver.observe(img));
        }

        loadImage(img) {
            const src = img.getAttribute('data-src') || img.src;
            if (src) {
                img.src = src;
                img.classList.add('loaded');
            }
        }

        loadAllImages() {
            this.images.forEach(img => this.loadImage(img));
        }
    }

    // ============================================
    // 2. تحسين التمرير (Scroll Performance)
    // ============================================
    
    class ScrollOptimizer {
        constructor() {
            this.lastScrollTop = 0;
            this.navbar = document.querySelector('.navbar');
            this.init();
        }

        init() {
            let ticking = false;

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        this.handleScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });
        }

        handleScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // إخفاء/إظهار Navbar عند التمرير
            if (this.navbar) {
                if (scrollTop > this.lastScrollTop && scrollTop > 100) {
                    this.navbar.classList.add('hidden');
                } else {
                    this.navbar.classList.remove('hidden');
                }
            }

            this.lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        }
    }

    // ============================================
    // 3. تحسين الأنيميشن (Animation Performance)
    // ============================================
    
    class AnimationOptimizer {
        constructor() {
            this.elements = document.querySelectorAll('[data-aos]');
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                this.observeElements();
            }
        }

        observeElements() {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('aos-animate');
                    }
                });
            }, {
                threshold: 0.1
            });

            this.elements.forEach(el => animationObserver.observe(el));
        }
    }

    // ============================================
    // 4. تحسين الـ Forms
    // ============================================
    
    class FormOptimizer {
        constructor() {
            this.forms = document.querySelectorAll('form');
            this.init();
        }

        init() {
            this.forms.forEach(form => {
                // Auto-save للنماذج الطويلة
                this.setupAutoSave(form);
                
                // Validation في الوقت الفعلي
                this.setupRealTimeValidation(form);
            });
        }

        setupAutoSave(form) {
            if (!form.hasAttribute('data-autosave')) return;

            const inputs = form.querySelectorAll('input, textarea, select');
            const formId = form.id || 'form-' + Date.now();

            // استرجاع البيانات المحفوظة
            this.restoreFormData(formId, inputs);

            // حفظ البيانات عند التغيير
            inputs.forEach(input => {
                input.addEventListener('input', this.debounce(() => {
                    this.saveFormData(formId, inputs);
                }, 1000));
            });
        }

        saveFormData(formId, inputs) {
            const data = {};
            inputs.forEach(input => {
                if (input.name) {
                    data[input.name] = input.value;
                }
            });
            localStorage.setItem(formId, JSON.stringify(data));
            
            // إظهار إشعار الحفظ
            if (typeof showInfo === 'function') {
                showInfo('تم الحفظ تلقائياً');
            }
        }

        restoreFormData(formId, inputs) {
            const savedData = localStorage.getItem(formId);
            if (savedData) {
                const data = JSON.parse(savedData);
                inputs.forEach(input => {
                    if (input.name && data[input.name]) {
                        input.value = data[input.name];
                    }
                });
            }
        }

        setupRealTimeValidation(form) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', () => {
                    this.validateInput(input);
                });
            });
        }

        validateInput(input) {
            if (input.validity.valid) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
            }
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    // ============================================
    // 5. تحسين الـ Search (Live Search)
    // ============================================
    
    class SearchOptimizer {
        constructor() {
            this.searchInputs = document.querySelectorAll('input[type="search"], .search-input');
            this.init();
        }

        init() {
            this.searchInputs.forEach(input => {
                input.addEventListener('input', this.debounce((e) => {
                    this.handleSearch(e.target.value);
                }, 300));
            });
        }

        handleSearch(query) {
            if (query.length < 3) return;

            // يمكن إضافة AJAX request هنا للبحث المباشر
            console.log('Searching for:', query);
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    // ============================================
    // 6. تحسين الـ Stats Counter
    // ============================================
    
    class StatsCounter {
        constructor() {
            this.counters = document.querySelectorAll('.stats-number[data-count]');
            this.init();
        }

        init() {
            if ('IntersectionObserver' in window) {
                this.observeCounters();
            }
        }

        observeCounters() {
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.animateCounter(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });

            this.counters.forEach(counter => counterObserver.observe(counter));
        }

        animateCounter(element) {
            const target = parseInt(element.getAttribute('data-count'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current);
                }
            }, 16);
        }
    }

    // ============================================
    // 7. تحسين الـ Performance Monitoring
    // ============================================
    
    class PerformanceMonitor {
        constructor() {
            this.init();
        }

        init() {
            if ('PerformanceObserver' in window) {
                this.observePerformance();
            }

            // قياس أداء الصفحة
            window.addEventListener('load', () => {
                this.measurePagePerformance();
            });
        }

        observePerformance() {
            // مراقبة Largest Contentful Paint
            const lcpObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);
            });
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });

            // مراقبة First Input Delay
            const fidObserver = new PerformanceObserver((list) => {
                const entries = list.getEntries();
                entries.forEach(entry => {
                    console.log('FID:', entry.processingStart - entry.startTime);
                });
            });
            fidObserver.observe({ entryTypes: ['first-input'] });
        }

        measurePagePerformance() {
            const perfData = window.performance.timing;
            const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart;
            const connectTime = perfData.responseEnd - perfData.requestStart;
            const renderTime = perfData.domComplete - perfData.domLoading;

            console.log('Page Performance:');
            console.log('- Page Load Time:', pageLoadTime + 'ms');
            console.log('- Connect Time:', connectTime + 'ms');
            console.log('- Render Time:', renderTime + 'ms');
        }
    }

    // ============================================
    // 8. تهيئة جميع المحسنات
    // ============================================
    
    function initOptimizations() {
        // تحميل الصور بشكل كسول
        new LazyImageLoader();

        // تحسين التمرير
        new ScrollOptimizer();

        // تحسين الأنيميشن
        new AnimationOptimizer();

        // تحسين النماذج
        new FormOptimizer();

        // تحسين البحث
        new SearchOptimizer();

        // عداد الإحصائيات
        new StatsCounter();

        // مراقبة الأداء (في بيئة التطوير فقط)
        if (window.location.hostname === 'localhost') {
            new PerformanceMonitor();
        }
    }

    // تشغيل التحسينات عند تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initOptimizations);
    } else {
        initOptimizations();
    }

})();

