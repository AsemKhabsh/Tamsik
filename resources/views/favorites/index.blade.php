@extends('layouts.app')

@section('title', 'المفضلات')

@section('content')
<style>
    .favorites-header {
        background: linear-gradient(135deg, #1d8a4e 0%, #15693a 100%);
        color: white;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 30px;
    }
    .filter-tabs {
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .filter-tabs .nav-link {
        color: #666;
        border: none;
        padding: 10px 20px;
        margin: 0 5px;
        border-radius: 5px;
    }
    .filter-tabs .nav-link.active {
        background: #1d8a4e;
        color: white;
    }
    .favorite-card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
        margin-bottom: 20px;
        transition: transform 0.3s;
    }
    .favorite-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    .favorite-type-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
    }
    .badge-sermon { background: #1d8a4e; color: white; }
    .badge-article { background: #2196F3; color: white; }
    .badge-fatwa { background: #FF9800; color: white; }
    .badge-lecture { background: #9C27B0; color: white; }
    .stats-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }
    .stats-card h3 {
        color: #1d8a4e;
        font-size: 32px;
        margin-bottom: 5px;
    }
    .stats-card p {
        color: #666;
        margin: 0;
    }
</style>

<div class="container py-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- العنوان -->
    <div class="favorites-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2><i class="fas fa-heart me-3"></i>مفضلاتي</h2>
                <p class="mb-0">جميع المحتويات المفضلة لديك في مكان واحد</p>
            </div>
            <div class="col-md-4 text-end">
                @if($stats['total'] > 0)
                    <form action="{{ route('favorites.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light" onclick="return confirm('هل أنت متأكد من حذف جميع المفضلات؟')">
                            <i class="fas fa-trash me-2"></i>حذف الكل
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- الإحصائيات -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="stats-card">
                <h3>{{ $stats['total'] }}</h3>
                <p>إجمالي المفضلات</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stats-card">
                <h3>{{ $stats['sermons'] }}</h3>
                <p>خطب</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stats-card">
                <h3>{{ $stats['articles'] }}</h3>
                <p>مقالات</p>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="stats-card">
                <h3>{{ $stats['fatwas'] }}</h3>
                <p>فتاوى</p>
            </div>
        </div>
    </div>

    <!-- التبويبات -->
    <div class="filter-tabs">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <a class="nav-link {{ $type == 'all' ? 'active' : '' }}" href="{{ route('favorites', ['type' => 'all']) }}">
                    <i class="fas fa-th-large me-2"></i>الكل
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'sermons' ? 'active' : '' }}" href="{{ route('favorites', ['type' => 'sermons']) }}">
                    <i class="fas fa-mosque me-2"></i>الخطب
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'articles' ? 'active' : '' }}" href="{{ route('favorites', ['type' => 'articles']) }}">
                    <i class="fas fa-newspaper me-2"></i>المقالات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'fatwas' ? 'active' : '' }}" href="{{ route('favorites', ['type' => 'fatwas']) }}">
                    <i class="fas fa-gavel me-2"></i>الفتاوى
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'lectures' ? 'active' : '' }}" href="{{ route('favorites', ['type' => 'lectures']) }}">
                    <i class="fas fa-chalkboard-teacher me-2"></i>المحاضرات
                </a>
            </li>
        </ul>
    </div>

    <!-- المحتوى -->
    @if($favorites->count() > 0)
        <div class="row">
            @foreach($favorites as $favorite)
                @php
                    $item = $favorite->favoritable;
                    $typeClass = '';
                    $typeName = '';
                    $icon = '';
                    $route = '';

                    if($item instanceof \App\Models\Sermon) {
                        $typeClass = 'badge-sermon';
                        $typeName = 'خطبة';
                        $icon = 'fa-mosque';
                        $route = route('sermons.show', $item->id);
                    } elseif($item instanceof \App\Models\Article) {
                        $typeClass = 'badge-article';
                        $typeName = 'مقالة';
                        $icon = 'fa-newspaper';
                        $route = route('articles.show', $item->id);
                    } elseif($item instanceof \App\Models\Fatwa) {
                        $typeClass = 'badge-fatwa';
                        $typeName = 'فتوى';
                        $icon = 'fa-gavel';
                        $route = route('fatwas.show', $item->id);
                    } elseif($item instanceof \App\Models\Lecture) {
                        $typeClass = 'badge-lecture';
                        $typeName = 'محاضرة';
                        $icon = 'fa-chalkboard-teacher';
                        $route = route('lectures.show', $item->id);
                    }
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card favorite-card">
                        <span class="favorite-type-badge {{ $typeClass }}">
                            <i class="fas {{ $icon }} me-1"></i>{{ $typeName }}
                        </span>

                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <a href="{{ $route }}" class="text-decoration-none text-dark">
                                    {{ $item->title ?? $item->question ?? 'بدون عنوان' }}
                                </a>
                            </h5>

                            @if(isset($item->description))
                                <p class="card-text text-muted small">
                                    {{ Str::limit($item->description, 100) }}
                                </p>
                            @endif

                            @if(isset($item->scholar))
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $item->scholar->name }}
                                </p>
                            @endif

                            @if(isset($item->category))
                                <span class="badge bg-secondary mb-2">{{ $item->category }}</span>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $favorite->created_at->diffForHumans() }}
                                </small>

                                <div>
                                    <a href="{{ $route }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger remove-favorite"
                                            data-type="{{ get_class($item) }}"
                                            data-id="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $favorites->links() }}
        </div>
    @else
        <!-- حالة فارغة -->
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-heart fa-5x text-muted mb-4"></i>
                <h3 class="text-muted mb-3">لا توجد مفضلات</h3>
                <p class="lead">
                    @if($type == 'all')
                        لم تقم بإضافة أي محتوى للمفضلات بعد
                    @elseif($type == 'sermons')
                        لم تقم بإضافة أي خطب للمفضلات بعد
                    @elseif($type == 'articles')
                        لم تقم بإضافة أي مقالات للمفضلات بعد
                    @elseif($type == 'fatwas')
                        لم تقم بإضافة أي فتاوى للمفضلات بعد
                    @elseif($type == 'lectures')
                        لم تقم بإضافة أي محاضرات للمفضلات بعد
                    @endif
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-home me-2"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // حذف مفضلة
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            if(!confirm('هل أنت متأكد من إزالة هذا العنصر من المفضلات؟')) {
                return;
            }

            const type = this.dataset.type;
            const id = this.dataset.id;

            fetch('{{ route("favorites.destroy") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    favoritable_type: type,
                    favoritable_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'حدث خطأ أثناء الحذف');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الحذف');
            });
        });
    });
});
</script>
@endsection

