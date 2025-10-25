@extends('admin.layout')

@section('title', 'إدارة الفتاوى')
@section('page-title', 'إدارة الفتاوى')
@section('page-description', 'عرض وإدارة جميع الفتاوى في الموقع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">قائمة الفتاوى</h4>
        <small class="text-muted">إجمالي {{ $fatwas->total() }} فتوى</small>
    </div>
    <a href="{{ route('admin.fatwas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة فتوى جديدة
    </a>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.fatwas') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="البحث في السؤال أو الإجابة" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">جميع التصنيفات</option>
                        <option value="worship" {{ request('category') == 'worship' ? 'selected' : '' }}>العبادات</option>
                        <option value="transactions" {{ request('category') == 'transactions' ? 'selected' : '' }}>المعاملات</option>
                        <option value="family" {{ request('category') == 'family' ? 'selected' : '' }}>الأسرة</option>
                        <option value="contemporary" {{ request('category') == 'contemporary' ? 'selected' : '' }}>القضايا المعاصرة</option>
                        <option value="ethics" {{ request('category') == 'ethics' ? 'selected' : '' }}>الأخلاق والآداب</option>
                        <option value="beliefs" {{ request('category') == 'beliefs' ? 'selected' : '' }}>العقيدة</option>
                        <option value="jurisprudence" {{ request('category') == 'jurisprudence' ? 'selected' : '' }}>الفقه</option>
                        <option value="quran" {{ request('category') == 'quran' ? 'selected' : '' }}>القرآن الكريم</option>
                        <option value="hadith" {{ request('category') == 'hadith' ? 'selected' : '' }}>الحديث الشريف</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>منشور</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
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
                    <select name="scholar" class="form-select">
                        <option value="">جميع العلماء</option>
                        @foreach(\App\Models\User::role('scholar')->where('is_active', true)->get() as $scholar)
                            <option value="{{ $scholar->id }}" {{ request('scholar') == $scholar->id ? 'selected' : '' }}>
                                {{ $scholar->name }}
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

<!-- جدول الفتاوى -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>العالم المجيب</th>
                        <th>التصنيف</th>
                        <th>الحالة</th>
                        <th>المشاهدات</th>
                        <th>تاريخ النشر</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fatwas as $fatwa)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($fatwa->is_featured)
                                    <i class="fas fa-star text-warning me-2" title="فتوى مميزة"></i>
                                @endif
                                <div>
                                    <strong>{{ Str::limit($fatwa->title, 50) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($fatwa->question, 80) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($fatwa->scholar)
                                <span class="badge bg-info">{{ $fatwa->scholar->name }}</span>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $categories = [
                                    'worship' => 'العبادات',
                                    'transactions' => 'المعاملات',
                                    'family' => 'الأسرة',
                                    'contemporary' => 'القضايا المعاصرة',
                                    'ethics' => 'الأخلاق والآداب',
                                    'beliefs' => 'العقيدة',
                                    'jurisprudence' => 'الفقه',
                                    'quran' => 'القرآن الكريم',
                                    'hadith' => 'الحديث الشريف',
                                ];
                            @endphp
                            <span class="badge bg-secondary">{{ $categories[$fatwa->category] ?? $fatwa->category }}</span>
                        </td>
                        <td>
                            @if($fatwa->is_published)
                                <span class="badge bg-success">منشور</span>
                            @else
                                <span class="badge bg-warning">قيد المراجعة</span>
                            @endif
                        </td>
                        <td>
                            <i class="fas fa-eye text-muted"></i>
                            {{ number_format($fatwa->views_count) }}
                        </td>
                        <td>
                            @if($fatwa->published_at)
                                {{ $fatwa->published_at->format('Y-m-d') }}
                            @else
                                <span class="text-muted">غير منشور</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('fatwas.show', $fatwa->id) }}" class="btn btn-sm btn-outline-info" title="عرض" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.fatwas.edit', $fatwa) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="حذف" 
                                    onclick="confirmDelete({{ $fatwa->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <form id="delete-form-{{ $fatwa->id }}" 
                                action="{{ route('admin.fatwas.delete', $fatwa) }}" 
                                method="POST" 
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد فتاوى حالياً</p>
                            <a href="{{ route('admin.fatwas.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة فتوى جديدة
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($fatwas->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $fatwas->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(fatwaId) {
    if (confirm('هل أنت متأكد من حذف هذه الفتوى؟ لا يمكن التراجع عن هذا الإجراء.')) {
        document.getElementById('delete-form-' + fatwaId).submit();
    }
}
</script>
@endpush

@endsection

