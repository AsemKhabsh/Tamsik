@extends('admin.layout')

@section('title', 'إدارة الخطب')
@section('page-title', 'إدارة الخطب')
@section('page-description', 'عرض وإدارة جميع خطب الموقع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">قائمة الخطب</h4>
        <small class="text-muted">إجمالي {{ $sermons->total() }} خطبة</small>
    </div>
    <a href="{{ route('sermons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة خطبة جديدة
    </a>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.sermons') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="البحث في العنوان أو المحتوى" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">جميع التصنيفات</option>
                        <option value="aqeedah" {{ request('category') == 'aqeedah' ? 'selected' : '' }}>العقيدة</option>
                        <option value="fiqh" {{ request('category') == 'fiqh' ? 'selected' : '' }}>الفقه</option>
                        <option value="akhlaq" {{ request('category') == 'akhlaq' ? 'selected' : '' }}>الأخلاق</option>
                        <option value="seerah" {{ request('category') == 'seerah' ? 'selected' : '' }}>السيرة</option>
                        <option value="occasions" {{ request('category') == 'occasions' ? 'selected' : '' }}>المناسبات</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="featured" class="form-select">
                        <option value="">الكل</option>
                        <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>مميز</option>
                        <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>غير مميز</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="author" class="form-select">
                        <option value="">جميع المؤلفين</option>
                        @foreach(\App\Models\User::where('role', 'scholar')->orWhere('role', 'admin')->get() as $author)
                            <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول الخطب -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>المؤلف</th>
                        <th>التصنيف</th>
                        <th>الحالة</th>
                        <th>مميز</th>
                        <th>المشاهدات</th>
                        <th>التحميلات</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sermons as $sermon)
                    <tr>
                        <td>
                            <div>
                                <h6 class="mb-1">{{ Str::limit($sermon->title, 40) }}</h6>
                                @if($sermon->introduction)
                                    <small class="text-muted">{{ Str::limit($sermon->introduction, 60) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($sermon->author)
                                <div class="d-flex align-items-center">
                                    @if($sermon->author->image)
                                        <img src="{{ asset('storage/' . $sermon->author->image) }}" alt="{{ $sermon->author->name }}" class="rounded-circle me-2" width="30" height="30">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                        </div>
                                    @endif
                                    <small>{{ $sermon->author->name }}</small>
                                </div>
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $sermon->category }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $sermon->is_published ? 'success' : 'warning' }}">
                                {{ $sermon->is_published ? 'منشور' : 'مسودة' }}
                            </span>
                        </td>
                        <td>
                            @if($sermon->is_featured)
                                <i class="fas fa-star text-warning" title="خطبة مميزة"></i>
                            @else
                                <i class="far fa-star text-muted" title="غير مميزة"></i>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ number_format($sermon->views_count ?? 0) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ number_format($sermon->downloads_count ?? 0) }}</span>
                        </td>
                        <td>{{ $sermon->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('sermons.show', $sermon) }}" class="btn btn-sm btn-outline-info" title="عرض" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.sermons.edit', $sermon) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sermons.delete', $sermon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirmDelete('هل أنت متأكد من حذف هذه الخطبة؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد خطب</h5>
                            <p class="text-muted">لم يتم العثور على أي خطب مطابقة للبحث</p>
                            <a href="{{ route('sermons.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة خطبة جديدة
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($sermons->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $sermons->links() }}
            </div>
        @endif
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mt-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">{{ $sermons->where('is_published', true)->count() }}</h4>
                <p class="text-muted mb-0">منشور</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-warning">{{ $sermons->where('is_published', false)->count() }}</h4>
                <p class="text-muted mb-0">مسودة</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-primary">{{ $sermons->where('is_featured', true)->count() }}</h4>
                <p class="text-muted mb-0">مميز</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-info">{{ $sermons->where('category', 'aqeedah')->count() }}</h4>
                <p class="text-muted mb-0">عقيدة</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-secondary">{{ $sermons->where('category', 'fiqh')->count() }}</h4>
                <p class="text-muted mb-0">فقه</p>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-dark">{{ $sermons->where('category', 'akhlaq')->count() }}</h4>
                <p class="text-muted mb-0">أخلاق</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تأكيد الحذف
    function confirmDelete(message) {
        return confirm(message);
    }
    
    // تحديد/إلغاء تحديد جميع الخطب
    function toggleAll(source) {
        const checkboxes = document.querySelectorAll('input[name="sermon_ids[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }
    
    // إجراءات مجمعة
    function bulkAction() {
        const action = document.getElementById('bulk-action').value;
        const selected = document.querySelectorAll('input[name="sermon_ids[]"]:checked');
        
        if (selected.length === 0) {
            alert('يرجى تحديد خطبة واحدة على الأقل');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('هل أنت متأكد من حذف الخطب المحددة؟')) {
                return;
            }
        }
        
        // تنفيذ الإجراء
        // يمكن إضافة AJAX هنا
    }
</script>
@endpush
