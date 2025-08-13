// ملف JavaScript الخاص بصفحة الخطب الجاهزة

// دالة بسيطة لعرض الأخطاء
function showSimpleError(message) {
    // إنشاء عنصر الخطأ
    const errorDiv = document.createElement('div');
    errorDiv.className = 'simple-error-message';
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 10000;
        max-width: 400px;
        font-family: 'Amiri', serif;
        direction: rtl;
    `;

    errorDiv.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()"
                    style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; margin-right: 10px;">
                ×
            </button>
        </div>
    `;

    document.body.appendChild(errorDiv);

    // إزالة الرسالة تلقائياً بعد 5 ثوان
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

// وظيفة تحميل الخطب من الخادم
async function loadSermons() {
    try {
        // عرض مؤشر التحميل
        showLoadingState();

        // التحقق من وجود معالج الأخطاء
        if (!window.errorHandler) {
            console.warn('معالج الأخطاء غير متاح، سيتم استخدام معالج بديل');
            window.errorHandler = {
                handleApiError: async (response, message) => {
                    console.error(`خطأ API: ${response.status} - ${message}`);
                    showSimpleError(message);
                },
                handleNetworkError: (error) => {
                    console.error('خطأ في الشبكة:', error);
                    showSimpleError('فشل في الاتصال بالخادم، تحقق من اتصال الإنترنت');
                }
            };
        }

        // جلب الخطب من API
        console.log('🔄 جاري جلب الخطب من API...');
        const response = await fetch('/api/sermons');

        console.log('📡 استجابة API:', response.status, response.statusText);

        if (!response.ok) {
            console.error('❌ خطأ في استجابة API:', response.status);
            if (window.errorHandler) {
                await window.errorHandler.handleApiError(response, 'فشل في تحميل الخطب');
            } else {
                showSimpleError(`فشل في تحميل الخطب: ${response.status}`);
            }
            return;
        }

        const data = await response.json();
        console.log('📊 البيانات المستلمة:', data);
        const sermons = data.data?.sermons || [];

        console.log('الخطب المحملة:', sermons);

        // تطبيق الفلترة والترتيب
        const filteredSermons = applyFiltersAndSort(sermons);

        // عرض الخطب
        displaySermons(filteredSermons);

        // إخفاء مؤشر التحميل
        hideLoadingState();

    } catch (error) {
        console.error('❌ خطأ في تحميل الخطب:', error);
        console.error('تفاصيل الخطأ:', {
            name: error.name,
            message: error.message,
            stack: error.stack
        });

        // عدم عرض رسالة خطأ إضافية إذا كان الخطأ fetch - سيتم التعامل معه محلياً
        if (!error.message.includes('fetch')) {
            if (window.errorHandler) {
                window.errorHandler.handleNetworkError(error);
            } else {
                showSimpleError('حدث خطأ في تحميل الخطب');
            }
        }

        hideLoadingState();

        // عرض الخطب المحفوظة محلياً كبديل
        console.log('🔄 التبديل إلى الخطب المحلية...');
        loadLocalSermons();
    }
}

// تطبيق الفلترة والترتيب
function applyFiltersAndSort(sermons) {
    const categoryFilter = document.getElementById('category');
    const sortOrder = document.getElementById('sort');

    let filteredSermons = [...sermons];

    // فلترة حسب التصنيف
    if (categoryFilter && categoryFilter.value !== 'all') {
        const categoryMap = {
            'aqeedah': 'العقيدة',
            'fiqh': 'الفقه',
            'akhlaq': 'الأخلاق',
            'seerah': 'السيرة النبوية',
            'occasions': 'المناسبات'
        };

        const arabicCategory = categoryMap[categoryFilter.value];
        filteredSermons = filteredSermons.filter(sermon => sermon.category === arabicCategory);
    }

    // ترتيب الخطب
    if (sortOrder && sortOrder.value) {
        switch(sortOrder.value) {
            case 'newest':
                filteredSermons.sort((a, b) => new Date(b.created_at || b.date) - new Date(a.created_at || a.date));
                break;
            case 'oldest':
                filteredSermons.sort((a, b) => new Date(a.created_at || a.date) - new Date(b.created_at || b.date));
                break;
            case 'popular':
                filteredSermons.sort((a, b) => (b.views || 0) - (a.views || 0));
                break;
        }
    }

    return filteredSermons;
}

// تحميل الخطب المحفوظة محلياً كبديل
function loadLocalSermons() {
    try {
        let sermons = JSON.parse(localStorage.getItem('sermons')) || [];

        // إذا لم تكن هناك خطب محفوظة، إضافة خطب تجريبية
        if (sermons.length === 0) {
            sermons = [
                {
                    id: 1,
                    title: 'خطبة تجريبية - التوكل على الله',
                    content: 'هذه خطبة تجريبية عن التوكل على الله...',
                    category: 'العقيدة',
                    author: 'مؤلف تجريبي',
                    created_at: new Date().toISOString(),
                    status: 'published'
                },
                {
                    id: 2,
                    title: 'خطبة تجريبية - أهمية الصلاة',
                    content: 'هذه خطبة تجريبية عن أهمية الصلاة...',
                    category: 'الفقه',
                    author: 'مؤلف تجريبي',
                    created_at: new Date().toISOString(),
                    status: 'published'
                }
            ];
            // حفظ الخطب التجريبية
            localStorage.setItem('sermons', JSON.stringify(sermons));
        }

        const filteredSermons = applyFiltersAndSort(sermons);
        displaySermons(filteredSermons);

        console.log('الخطب المخزنة:', sermons);

        if (sermons.length > 0 && window.errorHandler && window.errorHandler.showInfo) {
            window.errorHandler.showInfo('تم تحميل الخطب المحفوظة محلياً');
        }
    } catch (error) {
        console.error('خطأ في تحميل الخطب المحلية:', error);
        displayEmptyState();
    }
}

// عرض مؤشر التحميل
function showLoadingState() {
    const sermonsGrid = document.querySelector('.all-sermons .sermons-grid');
    if (sermonsGrid) {
        sermonsGrid.innerHTML = `
            <div class="loading-overlay">
                <div class="loading-spinner"></div>
                <p>جاري تحميل الخطب...</p>
            </div>
        `;
    }
}

// إخفاء مؤشر التحميل
function hideLoadingState() {
    const loadingOverlay = document.querySelector('.loading-overlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
}

// عرض حالة فارغة
function displayEmptyState() {
    const sermonsGrid = document.querySelector('.all-sermons .sermons-grid');
    const emptyState = document.getElementById('all-sermons-empty-state');

    if (sermonsGrid) {
        // إزالة أي خطب موجودة
        const existingCards = sermonsGrid.querySelectorAll('.sermon-card');
        existingCards.forEach(card => card.remove());

        // إظهار حالة الفراغ الافتراضية
        if (emptyState) {
            emptyState.style.display = 'block';
        }
    }
}

// عرض الخطب في الصفحة
function displaySermons(sermons) {
    const sermonsGrid = document.querySelector('.all-sermons .sermons-grid');
    if (!sermonsGrid) return;

    // إخفاء حالة الفراغ الافتراضية
    const emptyState = document.getElementById('all-sermons-empty-state');
    if (emptyState) {
        emptyState.style.display = 'none';
    }

    if (!sermons || sermons.length === 0) {
        displayEmptyState();
        return;
    }

    // إفراغ الشبكة من الخطب السابقة (لكن ليس من حالة الفراغ)
    const existingCards = sermonsGrid.querySelectorAll('.sermon-card');
    existingCards.forEach(card => card.remove());

    // إضافة الخطب المضافة من المستخدمين
    sermons.forEach(sermon => {
        const sermonCard = createSermonCard(sermon);
        sermonsGrid.appendChild(sermonCard);
    });

    // إضافة معالجات الأحداث للخطب الجديدة
    addSermonEventListeners();
}

// إنشاء بطاقة خطبة جديدة
function createSermonCard(sermon) {
    const sermonCard = document.createElement('div');
    sermonCard.className = 'sermon-card';

    sermonCard.innerHTML = `
        <div class="sermon-header">
            <span class="sermon-category">${sermon.category}</span>
            ${sermon.featured ? '<span class="featured-badge"><i class="fas fa-star"></i> مميزة</span>' : ''}
        </div>
        <h3 class="sermon-title">${sermon.title}</h3>
        <div class="sermon-meta">
            <span><i class="fas fa-user"></i> ${sermon.preacher}</span>
            <span><i class="fas fa-calendar"></i> ${formatDate(sermon.date)}</span>
        </div>
        <p class="sermon-excerpt">${sermon.excerpt}</p>
        <div class="sermon-footer">
            <div class="sermon-actions">
                <a href="sermon_details.html?id=${sermon.id}" class="btn btn-primary">قراءة الخطبة</a>
                <button class="btn btn-danger btn-sm delete-sermon" data-id="${sermon.id}">
                    <i class="fas fa-trash"></i> حذف
                </button>
            </div>
            <div class="sermon-stats">
                <span><i class="fas fa-eye"></i> ${sermon.views || 0}</span>
                <span><i class="fas fa-download"></i> ${sermon.downloads || 0}</span>
            </div>
        </div>
    `;

    // إضافة مستمع حدث لزر الحذف
    const deleteButton = sermonCard.querySelector('.delete-sermon');
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            const sermonId = this.getAttribute('data-id');
            if (confirm('هل أنت متأكد من رغبتك في حذف هذه الخطبة؟')) {
                deleteSermon(sermonId);
            }
        });
    }

    return sermonCard;
}

// تنسيق التاريخ
function formatDate(dateString) {
    const date = new Date(dateString);
    // يمكن تعديل هذه الدالة لعرض التاريخ بالتنسيق الهجري إذا لزم الأمر
    return date.toLocaleDateString('ar-SA');
}

// إدارة عرض قسم إضافة الخطبة حسب دور المستخدم (سيتم استدعاؤها من الاستدعاء الرئيسي)

    // مستمع حدث لتغيير الترتيب
    const sortOrder = document.getElementById('sort');
    if (sortOrder) {
        sortOrder.addEventListener('change', loadSermons);
    }

    // مستمع حدث للبحث
    const searchButton = document.getElementById('search-button');
    if (searchButton) {
        searchButton.addEventListener('click', searchSermons);
    }

    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchSermons();
            }
        });
    }

    // مستمعي أحداث لأزرار التصنيفات
    const categoryButtons = document.querySelectorAll('.category-btn');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // إزالة الفئة النشطة من جميع الأزرار
            categoryButtons.forEach(btn => btn.classList.remove('active'));

            // إضافة الفئة النشطة للزر المحدد
            this.classList.add('active');

            // تحديث قيمة فلتر التصنيف
            const category = this.getAttribute('data-category');
            if (categoryFilter) {
                categoryFilter.value = category;
                loadSermons();
            }
        });
    });
});

// وظيفة البحث عن الخطب
function searchSermons() {
    const searchInput = document.getElementById('search-input');
    if (!searchInput) return;

    const searchTerm = searchInput.value.trim().toLowerCase();
    const sermons = JSON.parse(localStorage.getItem('sermons')) || [];

    if (!searchTerm) {
        loadSermons();
        return;
    }

    const searchResults = sermons.filter(sermon =>
        sermon.title.toLowerCase().includes(searchTerm) ||
        sermon.preacher.toLowerCase().includes(searchTerm) ||
        sermon.excerpt.toLowerCase().includes(searchTerm) ||
        (sermon.content && sermon.content.toLowerCase().includes(searchTerm))
    );

    displaySermons(searchResults);
}

// إضافة دالة حذف الخطبة
function deleteSermon(sermonId) {
    let sermons = JSON.parse(localStorage.getItem('sermons')) || [];
    sermons = sermons.filter(sermon => sermon.id != sermonId);
    localStorage.setItem('sermons', JSON.stringify(sermons));

    // إعادة تحميل الخطب بعد الحذف
    loadSermons();

    // عرض رسالة نجاح
    showNotification('تم حذف الخطبة بنجاح', 'success');
}

// دالة لعرض إشعارات للمستخدم
function showNotification(message, type = 'info') {
    // إنشاء عنصر الإشعار
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
    `;

    // إضافة الإشعار إلى الصفحة
    document.body.appendChild(notification);

    // إظهار الإشعار بتأثير متحرك
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // إخفاء الإشعار بعد 3 ثوان
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);

    // إضافة مستمع حدث لزر الإغلاق
    const closeButton = notification.querySelector('.notification-close');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
    }
}

// إدارة عرض قسم إضافة الخطبة حسب دور المستخدم
function manageAddSermonSection() {
    const addSermonSection = document.getElementById('add-sermon-section');
    const authorizedCard = document.getElementById('add-sermon-authorized');
    const unauthorizedCard = document.getElementById('add-sermon-unauthorized');
    const guestCard = document.getElementById('add-sermon-guest');

    if (!addSermonSection) return;

    // التحقق من حالة تسجيل الدخول ودور المستخدم
    const isLoggedIn = window.authProtection?.isLoggedIn() || false;
    const currentUser = window.authProtection?.getCurrentUser() || null;

    // إخفاء جميع البطاقات أولاً
    if (authorizedCard) authorizedCard.style.display = 'none';
    if (unauthorizedCard) unauthorizedCard.style.display = 'none';
    if (guestCard) guestCard.style.display = 'none';

    if (!isLoggedIn) {
        // المستخدم غير مسجل دخول - عرض بطاقة الضيف
        if (guestCard) guestCard.style.display = 'block';
    } else if (currentUser) {
        // المستخدم مسجل دخول - التحقق من الدور
        const allowedRoles = ['admin', 'scholar', 'member']; // مشرف المنصة، عالم، خطيب

        if (allowedRoles.includes(currentUser.role)) {
            // المستخدم لديه صلاحية - عرض بطاقة المؤهلين
            if (authorizedCard) authorizedCard.style.display = 'block';
        } else {
            // المستخدم ليس لديه صلاحية - عرض بطاقة غير المؤهلين
            if (unauthorizedCard) unauthorizedCard.style.display = 'block';
        }
    } else {
        // حالة غير متوقعة - عرض بطاقة الضيف
        if (guestCard) guestCard.style.display = 'block';
    }
}

// تحميل الخطب المميزة
async function loadFeaturedSermons() {
    try {
        const response = await fetch('/api/sermons?featured=true');

        if (!response.ok) {
            console.error('فشل في تحميل الخطب المميزة:', response.status);
            return;
        }

        const data = await response.json();
        const featuredSermons = data.data?.sermons || [];

        displayFeaturedSermons(featuredSermons);

    } catch (error) {
        console.error('خطأ في تحميل الخطب المميزة:', error);
    }
}

// عرض الخطب المميزة
function displayFeaturedSermons(sermons) {
    const featuredGrid = document.querySelector('.featured-sermons .sermons-grid');
    const emptyState = document.getElementById('featured-empty-state');

    if (!featuredGrid) return;

    if (!sermons || sermons.length === 0) {
        // إظهار حالة الفراغ
        if (emptyState) {
            emptyState.style.display = 'block';
        }
        return;
    }

    // إخفاء حالة الفراغ
    if (emptyState) {
        emptyState.style.display = 'none';
    }

    // إزالة الخطب السابقة
    const existingCards = featuredGrid.querySelectorAll('.sermon-card');
    existingCards.forEach(card => card.remove());

    // إضافة الخطب المميزة
    sermons.forEach(sermon => {
        const sermonCard = createSermonCard(sermon);
        sermonCard.classList.add('featured');
        featuredGrid.appendChild(sermonCard);
    });
}

// تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 تحميل صفحة الخطب الجاهزة...');

    // تحميل الخطب العادية
    loadSermons();

    // تحميل الخطب المميزة
    loadFeaturedSermons();

    // إدارة عرض قسم إضافة الخطبة حسب دور المستخدم
    manageAddSermonSection();

    // إعداد أزرار التصنيف
    setupCategoryButtons();

    // إعداد البحث
    setupSearch();

    console.log('✅ تم تهيئة صفحة الخطب بنجاح');
});

// إعداد أزرار التصنيف
function setupCategoryButtons() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // إزالة الفئة النشطة من جميع الأزرار
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            // إضافة الفئة النشطة للزر المضغوط
            this.classList.add('active');

            const category = this.getAttribute('data-category');
            filterSermonsByCategory(category);
        });
    });
}

// إعداد البحث
function setupSearch() {
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');

    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault();
            const searchTerm = searchInput.value.trim();
            searchSermons(searchTerm);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                searchSermons(searchTerm);
            }
        });
    }
}

// إضافة معالجات الأحداث للخطب
function addSermonEventListeners() {
    // معالجة أزرار الحذف
    const deleteButtons = document.querySelectorAll('.delete-sermon');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sermonId = this.getAttribute('data-id');
            if (confirm('هل أنت متأكد من حذف هذه الخطبة؟')) {
                deleteSermon(sermonId);
            }
        });
    });
}

// حذف خطبة
function deleteSermon(sermonId) {
    try {
        // حذف من التخزين المحلي
        let sermons = JSON.parse(localStorage.getItem('sermons')) || [];
        sermons = sermons.filter(sermon => sermon.id != sermonId);
        localStorage.setItem('sermons', JSON.stringify(sermons));

        // إعادة تحميل الخطب
        loadLocalSermons();

        // عرض رسالة نجاح
        if (window.errorHandler && window.errorHandler.showSuccess) {
            window.errorHandler.showSuccess('تم حذف الخطبة بنجاح');
        }
    } catch (error) {
        console.error('خطأ في حذف الخطبة:', error);
        if (window.errorHandler && window.errorHandler.showError) {
            window.errorHandler.showError('فشل في حذف الخطبة');
        }
    }
}

// تصفية الخطب حسب التصنيف
function filterSermonsByCategory(category) {
    const sermonCards = document.querySelectorAll('.sermon-card');

    sermonCards.forEach(card => {
        const cardCategory = card.querySelector('.sermon-category');
        if (!cardCategory) return;

        const cardCategoryText = cardCategory.textContent.trim();

        // تحويل التصنيفات الإنجليزية إلى العربية
        const categoryMap = {
            'aqeedah': 'العقيدة',
            'fiqh': 'الفقه',
            'akhlaq': 'الأخلاق',
            'seerah': 'السيرة النبوية',
            'occasions': 'المناسبات'
        };

        const arabicCategory = categoryMap[category] || category;

        if (category === 'all' || cardCategoryText === arabicCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// البحث في الخطب
function searchSermons(searchTerm) {
    const sermonCards = document.querySelectorAll('.sermon-card');

    if (!searchTerm) {
        // إذا كان البحث فارغاً، عرض جميع الخطب
        sermonCards.forEach(card => {
            card.style.display = 'block';
        });
        return;
    }

    const searchTermLower = searchTerm.toLowerCase();

    sermonCards.forEach(card => {
        const title = card.querySelector('.sermon-title')?.textContent.toLowerCase() || '';
        const excerpt = card.querySelector('.sermon-excerpt')?.textContent.toLowerCase() || '';
        const meta = card.querySelector('.sermon-meta')?.textContent.toLowerCase() || '';

        if (title.includes(searchTermLower) ||
            excerpt.includes(searchTermLower) ||
            meta.includes(searchTermLower)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}