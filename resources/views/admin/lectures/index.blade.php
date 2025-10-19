@extends('admin.layout')

@section('title', 'إدارة المحاضرات')
@section('page-title', 'إدارة المحاضرات')
@section('page-description', 'عرض وإدارة جميع محاضرات الموقع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">قائمة المحاضرات</h4>
        <small class="text-muted">إجمالي {{ $lectures->total() }} محاضرة</small>
    </div>
    <a href="{{ route('lectures.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i>
        إضافة محاضرة جديدة
    </a>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.lectures') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="البحث في العنوان أو الوصف" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">جميع الحالات</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="speaker" class="form-control" placeholder="اسم المحاضر" value="{{ request('speaker') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="من تاريخ">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="إلى تاريخ">
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

<!-- جدول المحاضرات -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>المحاضر</th>
                        <th>المكان</th>
                        <th>التاريخ والوقت</th>
                        <th>الحالة</th>
                        <th>الملف الصوتي</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lectures as $lecture)
                    <tr>
                        <td>
                            <div>
                                <h6 class="mb-1">{{ Str::limit($lecture->title, 40) }}</h6>
                                @if($lecture->description)
                                    <small class="text-muted">{{ Str::limit($lecture->description, 60) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong>{{ $lecture->speaker ? $lecture->speaker->name : 'غير محدد' }}</strong>
                        </td>
                        <td>
                            @if($lecture->location)
                                <i class="fas fa-map-marker-alt text-muted"></i>
                                {{ $lecture->location }}
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            @if($lecture->scheduled_at)
                                <div>
                                    <strong>{{ $lecture->scheduled_at->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $lecture->scheduled_at->format('H:i') }}</small>
                                </div>
                            @else
                                <span class="text-muted">غير محدد</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'scheduled' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'secondary'
                                ];
                                $statusTexts = [
                                    'scheduled' => 'مجدولة',
                                    'completed' => 'مكتملة',
                                    'cancelled' => 'ملغية'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$lecture->status] ?? 'secondary' }}">
                                {{ $statusTexts[$lecture->status] ?? $lecture->status }}
                            </span>
                        </td>
                        <td>
                            @if($lecture->audio_file)
                                <a href="{{ asset('storage/' . $lecture->audio_file) }}" class="btn btn-sm btn-outline-success" target="_blank" title="تشغيل الملف الصوتي">
                                    <i class="fas fa-play"></i>
                                </a>
                            @else
                                <span class="text-muted">لا يوجد</span>
                            @endif
                        </td>
                        <td>{{ $lecture->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('lectures.show', $lecture) }}" class="btn btn-sm btn-outline-info" title="عرض" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-sm btn-outline-primary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.lectures.delete', $lecture) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirmDelete('هل أنت متأكد من حذف هذه المحاضرة؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-microphone fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد محاضرات</h5>
                            <p class="text-muted">لم يتم العثور على أي محاضرات مطابقة للبحث</p>
                            <a href="{{ route('lectures.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                إضافة محاضرة جديدة
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($lectures->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $lectures->links() }}
            </div>
        @endif
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-primary">{{ $lectures->where('status', 'scheduled')->count() }}</h4>
                <p class="text-muted mb-0">مجدولة</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">{{ $lectures->where('status', 'completed')->count() }}</h4>
                <p class="text-muted mb-0">مكتملة</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-secondary">{{ $lectures->where('status', 'cancelled')->count() }}</h4>
                <p class="text-muted mb-0">ملغية</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-info">{{ $lectures->whereNotNull('audio_file')->count() }}</h4>
                <p class="text-muted mb-0">بملف صوتي</p>
            </div>
        </div>
    </div>
</div>

<!-- محاضرات اليوم -->
@php
    $todayLectures = $lectures->filter(function($lecture) {
        return $lecture->scheduled_at && $lecture->scheduled_at->isToday();
    });
@endphp

@if($todayLectures->count() > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-calendar-day text-warning"></i>
            محاضرات اليوم ({{ $todayLectures->count() }})
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach($todayLectures as $lecture)
            <div class="col-md-6 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h6 class="card-title">{{ $lecture->title }}</h6>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> {{ $lecture->speaker ? $lecture->speaker->name : 'غير محدد' }}
                                @if($lecture->location)
                                    <br><i class="fas fa-map-marker-alt"></i> {{ $lecture->location }}
                                @endif
                                <br><i class="fas fa-clock"></i> {{ $lecture->scheduled_at->format('H:i') }}
                            </small>
                        </p>
                        <a href="{{ route('admin.lectures.edit', $lecture) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    // تأكيد الحذف
    function confirmDelete(message) {
        return confirm(message);
    }
    
    // تحديث حالة المحاضرة
    function updateStatus(lectureId, status) {
        if (confirm('هل أنت متأكد من تغيير حالة المحاضرة؟')) {
            // يمكن إضافة AJAX هنا
            fetch(`/admin/lectures/${lectureId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush
