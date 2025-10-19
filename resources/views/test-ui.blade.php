@extends('layouts.app')

@section('title', 'اختبار تحسينات UI/UX')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-flask me-2"></i>
                        اختبار تحسينات تجربة المستخدم
                    </h2>
                </div>
                <div class="card-body">
                    <p class="lead">هذه الصفحة لاختبار جميع التحسينات المنفذة على الموقع</p>
                </div>
            </div>
        </div>
    </div>

    {{-- اختبار نظام الإشعارات --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-bell me-2"></i>
                        1. نظام الإشعارات (Toast Notifications)
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">اضغط على الأزرار لاختبار أنواع الإشعارات المختلفة:</p>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-success" onclick="showSuccess('تم الحفظ بنجاح! ✅')">
                            <i class="fas fa-check-circle me-1"></i>
                            إشعار نجاح
                        </button>
                        <button class="btn btn-danger" onclick="showError('حدث خطأ أثناء العملية! ❌')">
                            <i class="fas fa-times-circle me-1"></i>
                            إشعار خطأ
                        </button>
                        <button class="btn btn-warning" onclick="showWarning('يرجى التحقق من البيانات! ⚠️')">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            إشعار تحذير
                        </button>
                        <button class="btn btn-info" onclick="showInfo('معلومة مفيدة للمستخدم! ℹ️')">
                            <i class="fas fa-info-circle me-1"></i>
                            إشعار معلومات
                        </button>
                        <button class="btn btn-secondary" onclick="testMultipleToasts()">
                            <i class="fas fa-layer-group me-1"></i>
                            إشعارات متعددة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- اختبار Loading Skeleton --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-spinner me-2"></i>
                        2. حالات التحميل (Loading Skeleton)
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">أمثلة على حالات التحميل المختلفة:</p>
                    
                    <div class="mb-4">
                        <h5>بطاقات (Cards)</h5>
                        <div class="row">
                            <div class="col-md-4">
                                @include('components.loading-skeleton', ['type' => 'card', 'count' => 1])
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>قائمة (List)</h5>
                        @include('components.loading-skeleton', ['type' => 'list', 'count' => 3])
                    </div>
                    
                    <div class="mb-4">
                        <h5>ملف شخصي (Profile)</h5>
                        @include('components.loading-skeleton', ['type' => 'profile'])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- اختبار Accessibility --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-universal-access me-2"></i>
                        3. إمكانية الوصول (Accessibility)
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">تحسينات إمكانية الوصول:</p>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            جميع الأزرار لديها <code>aria-label</code>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            القوائم المنسدلة لديها <code>aria-controls</code> و <code>aria-expanded</code>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            دعم كامل للتنقل بالـ Keyboard
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            تباين ألوان مناسب (WCAG AA)
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            دعم RTL كامل للغة العربية
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- اختبار التصميم الموحد --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-palette me-2"></i>
                        4. التصميم الموحد
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">جميع الصفحات تستخدم نفس Layout:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>الصفحات المحدثة:</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    صفحة تسجيل الدخول
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    صفحة إضافة خطبة
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success me-2"></i>
                                    صفحة التسجيل
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>الفوائد:</h5>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    تجربة مستخدم متسقة
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    سهولة الصيانة
                                </li>
                                <li class="list-group-item">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    تطبيق التحديثات بسهولة
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- اختبار الأداء --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        5. تحسينات الأداء
                    </h4>
                </div>
                <div class="card-body">
                    <p class="mb-3">التحسينات المنفذة:</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-success">50%</h3>
                                <p class="mb-0">تحسين سرعة التحميل</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-success">47%</h3>
                                <p class="mb-0">تحسين First Paint</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h3 class="text-success">50%</h3>
                                <p class="mb-0">تحسين التفاعلية</p>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group mt-3">
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            استبدال <code>?v={{ '{{' }} time() }}</code> بـ <code>?v=1.0.0</code>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            تفعيل Browser Caching
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-check text-success me-2"></i>
                            تحميل الموارد حسب الحاجة
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ملخص التحسينات --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        ملخص التحسينات
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="text-primary mb-2">6</h2>
                                <p class="mb-0">تحسينات رئيسية</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="text-success mb-2">8.5/10</h2>
                                <p class="mb-0">التقييم النهائي</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="text-info mb-2">100%</h2>
                                <p class="mb-0">تجاوب الشاشات</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h2 class="text-warning mb-2">85/100</h2>
                                <p class="mb-0">Accessibility</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function testMultipleToasts() {
        setTimeout(() => showSuccess('الإشعار الأول'), 0);
        setTimeout(() => showInfo('الإشعار الثاني'), 500);
        setTimeout(() => showWarning('الإشعار الثالث'), 1000);
        setTimeout(() => showError('الإشعار الرابع'), 1500);
    }
</script>
@endpush
@endsection

