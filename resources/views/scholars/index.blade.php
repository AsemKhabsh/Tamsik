@extends('layouts.app')

@section('title', 'العلماء - تمسيك')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="section-title">
                <i class="fas fa-user-graduate me-2"></i>
                العلماء اليمنيين
            </h1>
            <p class="text-muted">تعرف على كبار العلماء اليمنيين واطلع على خطبهم وفتاواهم</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
                <a href="{{ route('scholars.ask-question') }}" class="btn btn-primary">
                    <i class="fas fa-question-circle me-2"></i>
                    اطرح سؤالاً
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    سجل دخولك لطرح سؤال
                </a>
            @endauth
        </div>
    </div>

        <div class="scholars-grid">
            @forelse($scholars as $scholar)
                <div class="scholar-card">
                    <div class="scholar-avatar">
                        @if($scholar->avatar)
                            <img src="{{ asset('storage/' . $scholar->avatar) }}" alt="{{ $scholar->name }}">
                        @else
                            <i class="fas fa-user-graduate"></i>
                        @endif
                    </div>
                    <div class="scholar-info">
                        <h3 class="scholar-name">{{ $scholar->name }}</h3>
                        <p class="scholar-title">{{ $scholar->title ?? 'عالم' }}</p>
                        <p class="scholar-bio">{{ Str::limit($scholar->bio, 100) }}</p>
                        
                        <div class="scholar-stats">
                            <div class="stat-item">
                                <i class="fas fa-book-open"></i>
                                <span>{{ $scholar->sermons_count }} خطبة</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $scholar->fatwas_count }} فتوى</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('scholars.show', $scholar->id) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i>
                            عرض الملف الشخصي
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-user-graduate"></i>
                    <h3>لا يوجد علماء متاحون حالياً</h3>
                    <p>سيتم إضافة المزيد من العلماء قريباً</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($scholars->hasPages())
            <div class="pagination-wrapper">
                {{ $scholars->links() }}
            </div>
        @endif
</div>
@endsection
