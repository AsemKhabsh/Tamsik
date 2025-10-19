@extends('admin.layout')

@section('title', 'إدارة العلماء')
@section('page-title', 'إدارة العلماء والمفكرين')
@section('page-description', 'عرض وإدارة جميع علماء ومفكري الموقع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">قائمة العلماء والمفكرين</h4>
        <small class="text-muted">إجمالي {{ $scholars->total() }} عالم ومفكر</small>
    </div>
    <a href="{{ route('admin.scholars.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة عالم جديد
    </a>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.scholars') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="البحث بالاسم أو اللقب" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="has_bio" class="form-select">
                        <option value="">الكل</option>
                        <option value="1" {{ request('has_bio') == '1' ? 'selected' : '' }}>لديه سيرة ذاتية</option>
                        <option value="0" {{ request('has_bio') == '0' ? 'selected' : '' }}>بدون سيرة ذاتية</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i>
                        بحث
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول العلماء -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>العالم/المفكر</th>
                        <th>البريد الإلكتروني</th>
                        <th>اللقب العلمي</th>
                        <th>الحالة</th>
                        <th>عدد الخطب</th>
                        <th>عدد المحاضرات</th>
                        <th>تاريخ التسجيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scholars as $scholar)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($scholar->image)
                                    <img src="{{ asset('storage/' . $scholar->image) }}" alt="{{ $scholar->name }}" class="rounded-circle me-3" width="50" height="50">
                                @else
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-graduate text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $scholar->name }}</h6>
                                    @if($scholar->bio)
                                        <small class="text-muted">{{ Str::limit($scholar->bio, 50) }}</small>
                                    @else
                                        <small class="text-muted">لا توجد سيرة ذاتية</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $scholar->email }}</td>
                        <td>
                            @if($scholar->title)
                                <span class="badge bg-info">{{ $scholar->title }}</span>
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $scholar->is_active ? 'success' : 'secondary' }}">
                                {{ $scholar->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $scholar->sermons_count ?? 0 }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $scholar->lectures_count ?? 0 }}</span>
                        </td>
                        <td>{{ $scholar->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('scholars.show', $scholar) }}" class="btn btn-sm btn-outline-info" title="عرض الملف الشخصي" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.scholars.edit', $scholar) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.scholars.delete', $scholar) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirmDelete('هل أنت متأكد من حذف هذا العالم؟ سيتم حذف جميع خطبه ومحاضراته أيضاً.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد علماء</h5>
                            <p class="text-muted">لم يتم العثور على أي علماء مطابقين للبحث</p>
                            <a href="{{ route('admin.scholars.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة عالم جديد
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($scholars->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $scholars->links() }}
            </div>
        @endif
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">{{ $scholars->where('is_active', true)->count() }}</h4>
                <p class="text-muted mb-0">نشطين</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-secondary">{{ $scholars->where('is_active', false)->count() }}</h4>
                <p class="text-muted mb-0">غير نشطين</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-info">{{ $scholars->whereNotNull('bio')->count() }}</h4>
                <p class="text-muted mb-0">لديهم سيرة ذاتية</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-warning">{{ $scholars->whereNotNull('image')->count() }}</h4>
                <p class="text-muted mb-0">لديهم صورة شخصية</p>
            </div>
        </div>
    </div>
</div>

<!-- العلماء الأكثر نشاطاً -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-star text-warning"></i>
            العلماء الأكثر نشاطاً
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @php
                $activeScholars = $scholars->where('is_active', true)->sortByDesc(function($scholar) {
                    return ($scholar->sermons_count ?? 0) + ($scholar->lectures_count ?? 0);
                })->take(6);
            @endphp
            
            @forelse($activeScholars as $scholar)
            <div class="col-md-4 mb-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        @if($scholar->image)
                            <img src="{{ asset('storage/' . $scholar->image) }}" alt="{{ $scholar->name }}" class="rounded-circle mb-2" width="60" height="60">
                        @else
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-graduate text-white"></i>
                            </div>
                        @endif
                        <h6 class="card-title">{{ $scholar->name }}</h6>
                        @if($scholar->title)
                            <small class="text-muted">{{ $scholar->title }}</small>
                        @endif
                        <div class="mt-2">
                            <span class="badge bg-primary me-1">{{ $scholar->sermons_count ?? 0 }} خطبة</span>
                            <span class="badge bg-info">{{ $scholar->lectures_count ?? 0 }} محاضرة</span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.scholars.edit', $scholar) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i>
                                تعديل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-muted text-center">لا توجد علماء نشطين حالياً</p>
            </div>
            @endforelse
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
    
    // تبديل حالة النشاط
    function toggleStatus(scholarId) {
        if (confirm('هل أنت متأكد من تغيير حالة النشاط؟')) {
            fetch(`/admin/scholars/${scholarId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush
