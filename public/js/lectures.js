// ملف JavaScript لصفحة المحاضرات والدروس

// متغيرات عامة
let lecturesData = [];
let allLectures = []; // نسخة احتياطية من جميع المحاضرات

// دوال API للمحاضرات
const LecturesAPI = {
    // الحصول على جميع المحاضرات
    async getAll(filters = {}) {
        try {
            const params = new URLSearchParams();

            if (filters.province) params.append('province', filters.province);
            if (filters.day_of_week) params.append('day_of_week', filters.day_of_week);
            if (filters.type) params.append('type', filters.type);
            if (filters.search) params.append('search', filters.search);
            if (filters.limit) params.append('limit', filters.limit);
            if (filters.offset) params.append('offset', filters.offset);

            const response = await fetch(`/api/lectures?${params}`);
            const data = await response.json();

            if (data.success) {
                return data.data;
            } else {
                throw new Error(data.message || 'فشل في جلب المحاضرات');
            }
        } catch (error) {
            console.error('خطأ في جلب المحاضرات:', error);
            showToast('خطأ في جلب المحاضرات: ' + error.message, 'error');
            return { lectures: [], pagination: { total: 0 } };
        }
    },

    // الحصول على إحصائيات المحاضرات
    async getStats() {
        try {
            const response = await fetch('/api/lectures/stats');
            const data = await response.json();

            if (data.success) {
                return data.data.stats;
            } else {
                throw new Error(data.message || 'فشل في جلب الإحصائيات');
            }
        } catch (error) {
            console.error('خطأ في جلب الإحصائيات:', error);
            return null;
        }
    },

    // إضافة محاضرة جديدة
    async create(lectureData) {
        try {
            const response = await fetch('/api/lectures', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${getAuthToken()}`
                },
                body: JSON.stringify(lectureData)
            });

            const data = await response.json();

            if (data.success) {
                showToast('تم إضافة المحاضرة بنجاح', 'success');
                return data.data;
            } else {
                throw new Error(data.message || 'فشل في إضافة المحاضرة');
            }
        } catch (error) {
            console.error('خطأ في إضافة المحاضرة:', error);
            showToast('خطأ في إضافة المحاضرة: ' + error.message, 'error');
            return null;
        }
    },

    // تحديث محاضرة
    async update(id, lectureData) {
        try {
            const response = await fetch(`/api/lectures/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${getAuthToken()}`
                },
                body: JSON.stringify(lectureData)
            });

            const data = await response.json();

            if (data.success) {
                showToast('تم تحديث المحاضرة بنجاح', 'success');
                return data.data;
            } else {
                throw new Error(data.message || 'فشل في تحديث المحاضرة');
            }
        } catch (error) {
            console.error('خطأ في تحديث المحاضرة:', error);
            showToast('خطأ في تحديث المحاضرة: ' + error.message, 'error');
            return null;
        }
    },

    // حذف محاضرة
    async delete(id) {
        try {
            const response = await fetch(`/api/lectures/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`
                }
            });

            const data = await response.json();

            if (data.success) {
                showToast('تم حذف المحاضرة بنجاح', 'success');
                return true;
            } else {
                throw new Error(data.message || 'فشل في حذف المحاضرة');
            }
        } catch (error) {
            console.error('خطأ في حذف المحاضرة:', error);
            showToast('خطأ في حذف المحاضرة: ' + error.message, 'error');
            return false;
        }
    }
};

// دالة للحصول على رمز المصادقة
function getAuthToken() {
    const userData = localStorage.getItem('currentUser');
    if (userData) {
        const user = JSON.parse(userData);
        return user.token;
    }
    return null;
}

// دالة لعرض رسائل التنبيه
function showToast(message, type = 'info') {
    // إنشاء عنصر التنبيه
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    // إضافة التنبيه للصفحة
    document.body.appendChild(toast);

    // إظهار التنبيه
    setTimeout(() => toast.classList.add('show'), 100);

    // إخفاء التنبيه بعد 3 ثوان
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// إضافة أنماط CSS للتحسينات الجديدة
const additionalStyles = `
    .loading-spinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .spinner {
        background: white;
        padding: 30px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .spinner i {
        font-size: 24px;
        color: #2c5530;
        margin-bottom: 10px;
    }

    .spinner span {
        display: block;
        color: #333;
        font-weight: bold;
    }

    .toast {
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%) translateY(-100px);
        background: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        transition: transform 0.3s ease;
        min-width: 300px;
        text-align: center;
    }

    .toast.show {
        transform: translateX(-50%) translateY(0);
    }

    .toast-success {
        border-right: 4px solid #28a745;
    }

    .toast-error {
        border-right: 4px solid #dc3545;
    }

    .toast-info {
        border-right: 4px solid #17a2b8;
    }

    .toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .toast-content i {
        font-size: 18px;
    }

    .toast-success .toast-content i {
        color: #28a745;
    }

    .toast-error .toast-content i {
        color: #dc3545;
    }

    .toast-info .toast-content i {
        color: #17a2b8;
    }
`;

// إضافة الأنماط للصفحة
const styleSheet = document.createElement('style');
styleSheet.textContent = additionalStyles;
document.head.appendChild(styleSheet);

// بيانات تجريبية للمحاضرات
const sampleLectures = [
    {
        id: 3,
        title: "التربية الإسلامية للأطفال",
        lecturer: "د. محمد عمر",
        province: "حضرموت",
        location: "مركز الدعوة والإرشاد",
        day: "الخميس",
        time: "16:00",
        type: "ندوة",
        description: "ندوة تربوية لأولياء الأمور حول أساليب التربية الإسلامية الصحيحة",
        contact: "777345678",
        status: "active"
    },
    {
        id: 4,
        title: "دورة تحفيظ القرآن الكريم",
        lecturer: "الشيخ محمد سعيد",
        province: "عدن",
        location: "مسجد أبان بن عثمان",
        day: "السبت",
        time: "17:00",
        type: "دورة",
        description: "دورة تدريبية لتعليم أحكام التجويد وحفظ القرآن الكريم",
        contact: "777789012",
        status: "active"
    },
    {
        id: 8,
        title: "الفقه المقارن",
        lecturer: "د. طاهر صلاح ",
        province: "مارب",
        location: "جامع الجامعة ",
        day: "الخميس",
        time: "18:30",
        type: "درس",
        description: "درس في الفقه المقارن بين المذاهب الإسلامية المختلفة",
        contact: "777654321",
        status: "active"
    }
];

// متغيرات عامة
let currentView = 'table';
let currentPage = 1;
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
const lecturesPerPage = 10;
let filteredLectures = [...lecturesData];
let currentUser = null;
let sortColumn = '';
let sortDirection = 'asc';

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 بدء تحميل صفحة المحاضرات...');

    // تحقق من حالة تسجيل الدخول
    checkUserLogin();

    // إضافة مستمعي الأحداث
    setupEventListeners();

    // تحميل المحاضرات من API
    await loadLecturesFromAPI();

    // عرض المحاضرات
    displayLectures();

    // إعداد الترقيم
    setupPagination();

    // إعداد التقويم
    setupCalendar();

    console.log('✅ تم تحميل صفحة المحاضرات بنجاح');
});

// تحقق من حالة تسجيل الدخول وإدارة صلاحيات إضافة المحاضرات
function checkUserLogin() {
    console.log('🔍 فحص حالة تسجيل الدخول...');

    // مستخدم تجريبي للاختبار (يمكن إزالته لاحقاً)
    // إذا لم يكن هناك مستخدم مسجل دخول، استخدم مستخدم تجريبي
    if (!localStorage.getItem('currentUser') && (!window.authProtection || !window.authProtection.isLoggedIn())) {
        console.log('🧪 إنشاء مستخدم تجريبي للاختبار...');
        const testUser = {
            id: 999,
            name: 'مستخدم تجريبي',
            role: 'member', // خطيب
            email: 'test@example.com'
        };
        localStorage.setItem('currentUser', JSON.stringify(testUser));
        console.log('✅ تم إنشاء مستخدم تجريبي:', testUser);
    }

    // استخدام نظام الحماية الموحد إذا كان متوفراً
    if (window.authProtection && window.authProtection.isLoggedIn()) {
        currentUser = window.authProtection.getCurrentUser();
        console.log('✅ مستخدم مسجل دخول عبر نظام الحماية:', currentUser);
        manageLectureAddPermissions();
    } else {
        // التحقق من التخزين المحلي كبديل
        const userData = localStorage.getItem('currentUser');
        if (userData) {
            try {
                currentUser = JSON.parse(userData);
                console.log('✅ مستخدم مسجل دخول عبر التخزين المحلي:', currentUser);
                manageLectureAddPermissions();
            } catch (error) {
                console.error('❌ خطأ في قراءة بيانات المستخدم:', error);
                currentUser = null;
                manageLectureAddPermissions();
            }
        } else {
            console.log('❌ لا يوجد مستخدم مسجل دخول');
            currentUser = null;
            manageLectureAddPermissions();
        }
    }
}

// دالة لإزالة المستخدم التجريبي (للاختبار)
function removeTestUser() {
    const userData = localStorage.getItem('currentUser');
    if (userData) {
        const user = JSON.parse(userData);
        if (user.id === 999) {
            localStorage.removeItem('currentUser');
            console.log('🗑️ تم إزالة المستخدم التجريبي');
            location.reload(); // إعادة تحميل الصفحة
        }
    }
}

// إضافة دالة للوحة التحكم (يمكن استدعاؤها من وحدة التحكم)
window.lectureDebug = {
    removeTestUser: removeTestUser,
    showCurrentUser: () => console.log('المستخدم الحالي:', currentUser),
    testPermissions: () => manageLectureAddPermissions()
};

// إدارة صلاحيات إضافة المحاضرات
function manageLectureAddPermissions() {
    console.log('🎯 إدارة صلاحيات إضافة المحاضرات...');
    console.log('👤 المستخدم الحالي:', currentUser);

    const addLectureBtn = document.getElementById('add-lecture-btn');
    const addInfoSection = document.getElementById('lecture-add-info');
    const addInfoMessage = document.getElementById('add-info-message');
    const infoActions = document.getElementById('info-actions');

    console.log('🔍 عناصر الواجهة:', {
        addLectureBtn: !!addLectureBtn,
        addInfoSection: !!addInfoSection,
        addInfoMessage: !!addInfoMessage,
        infoActions: !!infoActions
    });

    // الأدوار المسموح لها بإضافة المحاضرات (مشرف المنصة، عالم، خطيب)
    const allowedRoles = ['admin', 'scholar', 'member'];

    if (currentUser && allowedRoles.includes(currentUser.role)) {
        console.log('✅ المستخدم لديه صلاحية إضافة المحاضرات');

        // المستخدم لديه صلاحية - إظهار الزر
        if (addLectureBtn) {
            addLectureBtn.style.display = 'block';
            console.log('✅ تم إظهار زر إضافة المحاضرة');
        } else {
            console.error('❌ لم يتم العثور على زر إضافة المحاضرة');
        }

        // إخفاء قسم المعلومات للمستخدمين المؤهلين
        if (addInfoSection) {
            addInfoSection.style.display = 'none';
        }

    } else if (currentUser) {
        console.log('⚠️ المستخدم مسجل دخول لكن ليس لديه صلاحية');

        // المستخدم مسجل دخول لكن ليس لديه صلاحية
        if (addLectureBtn) {
            addLectureBtn.style.display = 'none';
        }

        // إظهار قسم المعلومات مع رسالة توضيحية
        if (addInfoSection && addInfoMessage && infoActions) {
            addInfoSection.style.display = 'block';
            addInfoMessage.textContent = 'إضافة المحاضرات متاحة للعلماء والمشائخ والخطباء ومشرفي المنصة فقط';
            infoActions.innerHTML = '<p style="color: #6c757d; margin: 0; font-size: 0.9rem;">للحصول على صلاحية إضافة المحاضرات، يرجى التواصل مع إدارة الموقع</p>';
        }

    } else {
        console.log('❌ زائر غير مسجل دخول');

        // زائر غير مسجل دخول
        if (addLectureBtn) {
            addLectureBtn.style.display = 'none';
        }

        // إظهار قسم المعلومات مع أزرار تسجيل الدخول
        if (addInfoSection && addInfoMessage && infoActions) {
            addInfoSection.style.display = 'block';
            addInfoMessage.textContent = 'يمكن للعلماء والمشائخ والخطباء ومشرفي المنصة إضافة محاضرات ودروس جديدة';
            infoActions.innerHTML = `
                <a href="login.html" class="btn btn-primary">تسجيل الدخول</a>
                <a href="register.html" class="btn btn-outline">إنشاء حساب</a>
            `;
        }
    }
}

// تحميل المحاضرات من API
async function loadLecturesFromAPI() {
    try {
        showLoadingSpinner(true);

        const result = await LecturesAPI.getAll({ limit: 100 });

        if (result && result.lectures) {
            // تحويل البيانات لتتوافق مع الواجهة
            lecturesData = result.lectures.map(lecture => ({
                id: lecture.id,
                title: lecture.title,
                lecturer: lecture.lecturer_name,
                province: lecture.province,
                location: lecture.location,
                day: lecture.day_of_week,
                time: lecture.time,
                type: lecture.type,
                description: lecture.description,
                contact: lecture.contact_info,
                status: lecture.is_active ? 'active' : 'inactive'
            }));

            allLectures = [...lecturesData];
            filteredLectures = [...lecturesData];

            console.log(`تم تحميل ${lecturesData.length} محاضرة من API`);
        }

        // تحميل المحاضرات المحلية وإضافتها
        loadLocalLectures();

        // إضافة بيانات تجريبية إذا لم تكن هناك محاضرات
        if (lecturesData.length === 0) {
            addSampleLectures();
        }

    } catch (error) {
        console.error('خطأ في تحميل المحاضرات:', error);
        showToast('خطأ في تحميل المحاضرات', 'error');

        // في حالة فشل API، تحميل البيانات المحلية فقط
        loadLocalLectures();
    } finally {
        showLoadingSpinner(false);
    }
}

// تحميل المحاضرات من التخزين المحلي
function loadLocalLectures() {
    try {
        const storedLectures = JSON.parse(localStorage.getItem('lectures')) || [];

        if (storedLectures.length > 0) {
            // إضافة المحاضرات المحلية إلى البيانات الموجودة
            storedLectures.forEach(localLecture => {
                // التحقق من عدم وجود المحاضرة مسبقاً (تجنب التكرار)
                const exists = lecturesData.some(lecture => lecture.id === localLecture.id);
                if (!exists) {
                    lecturesData.push(localLecture);
                }
            });

            // تحديث النسخ الاحتياطية
            allLectures = [...lecturesData];
            filteredLectures = [...lecturesData];

            console.log(`تم تحميل ${storedLectures.length} محاضرة من التخزين المحلي`);
        }
    } catch (error) {
        console.error('خطأ في تحميل المحاضرات المحلية:', error);
    }
}

// إضافة بيانات تجريبية
function addSampleLectures() {
    const sampleLectures = [
        {
            id: 1,
            title: 'درس في التفسير',
            lecturer: 'د. عبدالرحمن السريحي',
            province: 'مارب',
            location: 'جامع الجامعة',
            day: 'الجمعة',
            time: '15:00',
            type: 'درس',
            description: 'درس أسبوعي في تفسير القرآن الكريم',
            contact: '777123456',
            status: 'active'
        },
        {
            id: 2,
            title: 'محاضرة في الفقه',
            lecturer: 'الشيخ احمد حربة',
            province: 'مارب',
            location: 'جامع الرضوان ',
            day: 'الثلاثاء',
            time: '20:00',
            type: 'محاضرة',
            description: 'محاضرة شهرية في أحكام الفقه',
            contact: '733987654',
            status: 'active'
        },
        {
            id: 3,
            title: 'ندوة الشباب',
            lecturer: 'د. محمد سعيد',
            province: 'حضروموت',
            location: 'مركز الدعوة الإسلامية',
            day: 'السبت',
            time: '16:30',
            type: 'ندوة',
            description: 'ندوة أسبوعية موجهة للشباب',
            contact: '770555333',
            status: 'active'
        }
    ];

    lecturesData = [...sampleLectures];
    allLectures = [...sampleLectures];
    filteredLectures = [...sampleLectures];

    console.log('تم إضافة بيانات تجريبية للمحاضرات');
}

// إظهار/إخفاء مؤشر التحميل
function showLoadingSpinner(show) {
    let spinner = document.getElementById('loading-spinner');

    if (show && !spinner) {
        spinner = document.createElement('div');
        spinner.id = 'loading-spinner';
        spinner.className = 'loading-spinner';
        spinner.innerHTML = `
            <div class="spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span>جاري تحميل المحاضرات...</span>
            </div>
        `;
        document.body.appendChild(spinner);
    } else if (!show && spinner) {
        spinner.remove();
    }
}

// إعداد مستمعي الأحداث
function setupEventListeners() {
    // البحث
    const searchInput = document.getElementById('search-input');
    searchInput.addEventListener('input', debounce(filterLectures, 300));

    // الفلترة
    const provinceFilter = document.getElementById('province-filter');
    const dayFilter = document.getElementById('day-filter');
    const typeFilter = document.getElementById('type-filter');

    provinceFilter.addEventListener('change', filterLectures);
    dayFilter.addEventListener('change', filterLectures);
    typeFilter.addEventListener('change', filterLectures);

    // أزرار العرض
    const viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.getAttribute('data-view');
            switchView(view);
        });
    });

    // زر إضافة محاضرة
    const addLectureBtn = document.getElementById('add-lecture-btn');
    console.log('🔍 البحث عن زر إضافة المحاضرة:', !!addLectureBtn);
    if (addLectureBtn) {
        addLectureBtn.addEventListener('click', openAddLectureModal);
        console.log('✅ تم ربط مستمع الحدث بزر إضافة المحاضرة');
        console.log('👁️ حالة عرض الزر الحالية:', addLectureBtn.style.display);
    } else {
        console.error('❌ لم يتم العثور على زر إضافة المحاضرة');
    }

    // إغلاق النافذة المنبثقة
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('cancel-lecture');
    const modal = document.getElementById('add-lecture-modal');

    closeModal.addEventListener('click', closeAddLectureModal);
    cancelBtn.addEventListener('click', closeAddLectureModal);

    // إغلاق النافذة عند النقر خارجها
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeAddLectureModal();
        }
    });

    // نموذج إضافة محاضرة
    const addLectureForm = document.getElementById('add-lecture-form');
    addLectureForm.addEventListener('submit', handleAddLecture);

    // التقويم
    document.getElementById('prev-month').addEventListener('click', () => {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        setupCalendar();
    });

    document.getElementById('next-month').addEventListener('click', () => {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        setupCalendar();
    });
}

// فلترة المحاضرات
async function filterLectures() {
    const searchTerm = document.getElementById('search-input').value.trim();
    const provinceFilter = document.getElementById('province-filter').value;
    const dayFilter = document.getElementById('day-filter').value;
    const typeFilter = document.getElementById('type-filter').value;

    // إذا كانت هناك فلاتر، استخدم API
    if (searchTerm || provinceFilter || dayFilter || typeFilter) {
        try {
            showLoadingSpinner(true);

            const filters = {};
            if (searchTerm) filters.search = searchTerm;
            if (provinceFilter) filters.province = provinceFilter;
            if (dayFilter) filters.day_of_week = dayFilter;
            if (typeFilter) filters.type = typeFilter;
            filters.limit = 100;

            const result = await LecturesAPI.getAll(filters);

            if (result && result.lectures) {
                filteredLectures = result.lectures.map(lecture => ({
                    id: lecture.id,
                    title: lecture.title,
                    lecturer: lecture.lecturer_name,
                    province: lecture.province,
                    location: lecture.location,
                    day: lecture.day_of_week,
                    time: lecture.time,
                    type: lecture.type,
                    description: lecture.description,
                    contact: lecture.contact_info,
                    status: lecture.is_active ? 'active' : 'inactive'
                }));
            } else {
                filteredLectures = [];
            }
        } catch (error) {
            console.error('خطأ في فلترة المحاضرات:', error);
            filteredLectures = [];
        } finally {
            showLoadingSpinner(false);
        }
    } else {
        // إذا لم تكن هناك فلاتر، استخدم البيانات المحلية
        filteredLectures = [...allLectures];
    }

    currentPage = 1;
    displayLectures();
    setupPagination();
}

// تبديل العرض
function switchView(view) {
    currentView = view;

    // تحديث أزرار العرض
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`[data-view="${view}"]`).classList.add('active');

    // إخفاء جميع العروض
    document.getElementById('table-view').style.display = 'none';
    document.getElementById('cards-view').style.display = 'none';
    document.getElementById('calendar-view').style.display = 'none';

    // إظهار العرض المحدد
    switch(view) {
        case 'table':
            document.getElementById('table-view').style.display = 'block';
            displayTableView();
            break;
        case 'cards':
            document.getElementById('cards-view').style.display = 'block';
            displayCardsView();
            break;
        case 'calendar':
            document.getElementById('calendar-view').style.display = 'block';
            setupCalendar();
            break;
    }
}

// عرض المحاضرات
function displayLectures() {
    if (currentView === 'table') {
        displayTableView();
    } else if (currentView === 'cards') {
        displayCardsView();
    }

    // إظهار/إخفاء رسالة عدم وجود نتائج
    const noResults = document.getElementById('no-results');
    if (filteredLectures.length === 0) {
        noResults.style.display = 'block';
    } else {
        noResults.style.display = 'none';
    }
}

// عرض الجدول
function displayTableView() {
    const tableBody = document.getElementById('lectures-table-body');

    if (filteredLectures.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7">لا توجد محاضرات</td></tr>';
        return;
    }

    const startIndex = (currentPage - 1) * lecturesPerPage;
    const endIndex = startIndex + lecturesPerPage;
    const lecturesToShow = filteredLectures.slice(startIndex, endIndex);

    tableBody.innerHTML = lecturesToShow.map(lecture => `
        <tr>
            <td>${lecture.province}</td>
            <td>${lecture.lecturer}</td>
            <td>${lecture.location}</td>
            <td>${lecture.day}</td>
            <td>${formatTime(lecture.time)}</td>
            <td><span class="lecture-type-badge ${lecture.type}">${lecture.type}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view" onclick="viewLecture(${lecture.id})" title="عرض التفاصيل">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editLecture(${lecture.id})" title="تعديل">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteLecture(${lecture.id})" title="حذف">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// عرض البطاقات
function displayCardsView() {
    const lecturesGrid = document.getElementById('lectures-grid');

    if (filteredLectures.length === 0) {
        lecturesGrid.innerHTML = '<p>لا توجد محاضرات</p>';
        return;
    }

    const startIndex = (currentPage - 1) * lecturesPerPage;
    const endIndex = startIndex + lecturesPerPage;
    const lecturesToShow = filteredLectures.slice(startIndex, endIndex);

    lecturesGrid.innerHTML = lecturesToShow.map(lecture => createLectureCard(lecture)).join('');
}

// إنشاء بطاقة محاضرة
function createLectureCard(lecture) {
    return `
        <div class="lecture-card">
            <div class="lecture-card-header">
                <div class="lecture-type">${lecture.type}</div>
                <h3 class="lecture-title">${lecture.title}</h3>
                <div class="lecture-lecturer">
                    <i class="fas fa-user"></i>
                    ${lecture.lecturer}
                </div>
            </div>
            <div class="lecture-card-body">
                <div class="lecture-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${lecture.province}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-mosque"></i>
                        <span>${lecture.location}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <span>${lecture.day}</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>${formatTime(lecture.time)}</span>
                    </div>
                </div>
                ${lecture.description ? `<p class="lecture-description">${lecture.description}</p>` : ''}
            </div>
            <div class="lecture-card-footer">
                ${lecture.contact ? `<div class="contact-info"><i class="fas fa-phone"></i> ${lecture.contact}</div>` : ''}
                <div class="card-actions">
                    <button class="action-btn view" onclick="viewLecture(${lecture.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editLecture(${lecture.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteLecture(${lecture.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// إعداد التقويم
function setupCalendar() {
    const monthNames = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];

    document.getElementById('calendar-title').textContent = `${monthNames[currentMonth]} ${currentYear}`;

    const calendarGrid = document.getElementById('calendar-grid');
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    // أيام الأسبوع
    const dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    let headerHTML = '<div class="calendar-days-header">';
    dayNames.forEach(day => {
        headerHTML += `<div class="calendar-day-header">${day}</div>`;
    });
    headerHTML += '</div>';

    // أيام الشهر
    let daysHTML = '<div class="calendar-days-grid">';

    // الأيام الفارغة في بداية الشهر
    for (let i = 0; i < startingDayOfWeek; i++) {
        daysHTML += '<div class="calendar-day other-month"></div>';
    }

    // أيام الشهر
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = new Date().getDate() === day &&
                       new Date().getMonth() === currentMonth &&
                       new Date().getFullYear() === currentYear;

        const dayEvents = getLecturesForDay(day);

        daysHTML += `
            <div class="calendar-day ${isToday ? 'today' : ''}">
                <div class="day-number">${day}</div>
                <div class="day-events">
                    ${dayEvents.map(event => `<div class="event-item">${event.title}</div>`).join('')}
                </div>
            </div>
        `;
    }

    daysHTML += '</div>';

    calendarGrid.innerHTML = headerHTML + daysHTML;
}

// الحصول على المحاضرات لليوم المحدد
function getLecturesForDay(day) {
    const dayNames = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
    const date = new Date(currentYear, currentMonth, day);
    const dayName = dayNames[date.getDay()];

    return filteredLectures.filter(lecture => lecture.day === dayName);
}

// ترتيب الجدول
function sortTable(column) {
    if (sortColumn === column) {
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        sortColumn = column;
        sortDirection = 'asc';
    }

    filteredLectures.sort((a, b) => {
        let aValue = a[column];
        let bValue = b[column];

        if (column === 'time') {
            aValue = convertTimeToMinutes(aValue);
            bValue = convertTimeToMinutes(bValue);
        }

        if (sortDirection === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });

    displayLectures();
}

// تحويل الوقت إلى دقائق
function convertTimeToMinutes(time) {
    const [hours, minutes] = time.split(':').map(Number);
    return hours * 60 + minutes;
}

// إعداد الترقيم
function setupPagination() {
    const pagination = document.getElementById('pagination');
    const totalPages = Math.ceil(filteredLectures.length / lecturesPerPage);

    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let paginationHTML = '';

    // زر السابق
    paginationHTML += `
        <button ${currentPage === 1 ? 'disabled' : ''} onclick="changePage(${currentPage - 1})">
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

    // أرقام الصفحات
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `<button class="active">${i}</button>`;
        } else {
            paginationHTML += `<button onclick="changePage(${i})">${i}</button>`;
        }
    }

    // زر التالي
    paginationHTML += `
        <button ${currentPage === totalPages ? 'disabled' : ''} onclick="changePage(${currentPage + 1})">
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

    pagination.innerHTML = paginationHTML;
}

// تغيير الصفحة
function changePage(page) {
    currentPage = page;
    displayLectures();
    setupPagination();

    // التمرير إلى أعلى الصفحة
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// فتح نافذة إضافة محاضرة
function openAddLectureModal() {
    // التحقق من تسجيل الدخول
    if (!currentUser) {
        alert('يجب تسجيل الدخول أولاً');
        window.location.href = 'login.html';
        return;
    }

    // التحقق من الصلاحيات
    const allowedRoles = ['admin', 'scholar', 'member'];
    if (!allowedRoles.includes(currentUser.role)) {
        alert('ليس لديك صلاحية لإضافة محاضرة. يُسمح فقط للعلماء والمشائخ والخطباء ومشرفي المنصة بإضافة المحاضرات.');
        return;
    }

    const modal = document.getElementById('add-lecture-modal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// إغلاق نافذة إضافة محاضرة
function closeAddLectureModal() {
    const modal = document.getElementById('add-lecture-modal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';

    // إعادة تعيين النموذج
    document.getElementById('add-lecture-form').reset();
}

// معالجة إضافة محاضرة جديدة
function handleAddLecture(e) {
    e.preventDefault();

    // التحقق من تسجيل الدخول والصلاحيات
    if (!currentUser) {
        alert('يجب تسجيل الدخول أولاً');
        return;
    }

    const allowedRoles = ['admin', 'scholar', 'member'];
    if (!allowedRoles.includes(currentUser.role)) {
        alert('ليس لديك صلاحية لإضافة محاضرة. يُسمح فقط للعلماء والمشائخ والخطباء ومشرفي المنصة بإضافة المحاضرات.');
        return;
    }

    const title = document.getElementById('lecture-title').value;
    const type = document.getElementById('lecture-type').value;
    const lecturer = document.getElementById('lecture-lecturer').value;
    const province = document.getElementById('lecture-province').value;
    const location = document.getElementById('lecture-location').value;
    const day = document.getElementById('lecture-day').value;
    const time = document.getElementById('lecture-time').value;
    const description = document.getElementById('lecture-description').value;
    const contact = document.getElementById('lecture-contact').value;

    if (!title || !type || !lecturer || !province || !location || !day || !time) {
        alert('يرجى ملء جميع الحقول المطلوبة');
        return;
    }

    const newLecture = {
        id: Date.now(),
        title: title,
        type: type,
        lecturer: lecturer,
        province: province,
        location: location,
        day: day,
        time: time,
        description: description,
        contact: contact,
        status: 'pending' // يحتاج موافقة الإدارة
    };

    // إضافة المحاضرة إلى البيانات
    lecturesData.push(newLecture);
    allLectures.push(newLecture); // إضافة إلى النسخة الاحتياطية أيضاً

    // حفظ في التخزين المحلي
    const storedLectures = JSON.parse(localStorage.getItem('lectures')) || [];
    storedLectures.push(newLecture);
    localStorage.setItem('lectures', JSON.stringify(storedLectures));

    // تحديث العرض فوراً
    updateLecturesDisplay();

    // إغلاق النافذة
    closeAddLectureModal();

    // إظهار رسالة نجاح
    showToast('تم إضافة المحاضرة بنجاح!', 'success');

    // إرسال إلى الخادم (اختياري)
    saveLectureToServer(newLecture);
}

// تحديث عرض المحاضرات
function updateLecturesDisplay() {
    // تحديث البيانات المفلترة
    filteredLectures = [...lecturesData];

    // تحديث العرض الحالي
    displayLectures();

    // تحديث الترقيم
    setupPagination();
}

// حفظ المحاضرة في الخادم
async function saveLectureToServer(lectureData) {
    try {
        const result = await LecturesAPI.create(lectureData);
        if (result) {
            console.log('تم حفظ المحاضرة في الخادم بنجاح');
            // تحديث ID المحاضرة بالـ ID الجديد من الخادم
            const index = lecturesData.findIndex(l => l.id === lectureData.id);
            if (index !== -1 && result.id) {
                lecturesData[index].id = result.id;
                lecturesData[index].server_id = result.id;
            }
        }
    } catch (error) {
        console.error('خطأ في حفظ المحاضرة في الخادم:', error);
        // المحاضرة محفوظة محلياً، لذا لا نحتاج لإظهار خطأ للمستخدم
    }
}

// عرض تفاصيل المحاضرة
function viewLecture(id) {
    const lecture = lecturesData.find(l => l.id === id);
    if (lecture) {
        alert(`تفاصيل المحاضرة:\n\nالعنوان: ${lecture.title}\nالمحاضر: ${lecture.lecturer}\nالمكان: ${lecture.location}\nالوقت: ${lecture.day} - ${formatTime(lecture.time)}\nالوصف: ${lecture.description || 'غير متوفر'}`);
    }
}

// تعديل المحاضرة
function editLecture(id) {
    if (!currentUser) {
        alert('يجب تسجيل الدخول أولاً');
        return;
    }
    alert('ميزة التعديل ستكون متاحة قريباً');
}

// حذف المحاضرة
function deleteLecture(id) {
    if (!currentUser) {
        alert('يجب تسجيل الدخول أولاً');
        return;
    }

    if (confirm('هل أنت متأكد من حذف هذه المحاضرة؟')) {
        const index = lecturesData.findIndex(l => l.id === id);
        if (index !== -1) {
            lecturesData.splice(index, 1);

            // تحديث التخزين المحلي
            localStorage.setItem('lectures', JSON.stringify(lecturesData));

            // إعادة تحميل البيانات
            filteredLectures = [...lecturesData];
            displayLectures();
            setupPagination();

            alert('تم حذف المحاضرة بنجاح');
        }
    }
}

// تنسيق الوقت
function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour12 = hours % 12 || 12;
    const ampm = hours >= 12 ? 'مساءً' : 'صباحاً';
    return `${hour12}:${minutes} ${ampm}`;
}

// دالة تأخير للبحث
function debounce(func, wait) {
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
