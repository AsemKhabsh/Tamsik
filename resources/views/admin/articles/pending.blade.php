@extends('admin.layout')

@section('title', 'المقالات المعلقة - لوحة التحكم')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-clock me-2 text-warning"></i>
                    المقالات المعلقة (قيد المراجعة)
                </h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة للوحة التحكم
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة المقالات المعلقة ({{ $articles->total() }})
            </h5>
        </div>
        <div class="card-body">
            @if($articles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">العنوان</th>
                                <th width="15%">الكاتب</th>
                                <th width="10%">التصنيف</th>
                                <th width="10%">تاريخ الإنشاء</th>
                                <th width="10%">المشاهدات</th>
                                <th width="20%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    <td>{{ $article->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($article->featured_image)
                                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                     alt="{{ $article->title }}" 
                                                     class="rounded me-2" 
                                                     width="50" 
                                                     height="50"
                                                     style="object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ Str::limit($article->title, 50) }}</strong>
                                                @if($article->excerpt)
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($article->excerpt, 60) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($article->author->avatar)
                                                <img src="{{ asset('storage/' . $article->author->avatar) }}" 
                                                     alt="{{ $article->author->name }}" 
                                                     class="rounded-circle me-2" 
                                                     width="30" 
                                                     height="30">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 30px; height: 30px; font-size: 12px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                            <div>
                                                {{ $article->author->name }}
                                                <br>
                                                <small class="text-muted">
                                                    @if($article->author->user_type === 'scholar')
                                                        عالم
                                                    @elseif($article->author->user_type === 'thinker')
                                                        مفكر
                                                    @elseif($article->author->user_type === 'preacher')
                                                        خطيب
                                                    @else
                                                        {{ $article->author->user_type }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($article->category)
                                            <span class="badge bg-info">{{ $article->category->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $article->created_at->format('Y/m/d') }}</small>
                                        <br>
                                        <small class="text-muted">{{ $article->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <i class="fas fa-eye text-muted me-1"></i>
                                        {{ $article->views_count ?? 0 }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('articles.show', $article->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               target="_blank"
                                               title="معاينة">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.articles.approve', $article->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من نشر هذا المقال؟')">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="نشر">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.articles.reject', $article->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من رفض هذا المقال؟')">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-warning" 
                                                        title="رفض">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.articles.delete', $article->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال نهائياً؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger" 
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $articles->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4>لا توجد مقالات معلقة</h4>
                    <p class="text-muted">جميع المقالات تمت مراجعتها!</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة للوحة التحكم
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-right-radius: 0.25rem;
    border-bottom-right-radius: 0.25rem;
}

.btn-group .btn:last-child {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
}
</style>
@endsection

