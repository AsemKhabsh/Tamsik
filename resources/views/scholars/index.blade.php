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
            <div class="d-flex gap-2 justify-content-end flex-wrap">
                <a href="{{ route('fatwas.index') }}" class="btn btn-success">
                    <i class="fas fa-balance-scale me-2"></i>
                    تصفح الفتاوى
                </a>
                @auth
                    {{-- إخفاء زر اطرح سؤال عن العلماء لأنهم يجيبون على الأسئلة ولا يطرحونها --}}
                    @if(!auth()->user()->hasRole('scholar'))
                        <a href="{{ route('questions.ask') }}" class="btn btn-primary">
                            <i class="fas fa-question-circle me-2"></i>
                            اطرح سؤالاً
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        سجل دخولك لطرح سؤال
                    </a>
                @endauth
            </div>
        </div>
    </div>

        <div class="scholars-grid">
            @forelse($scholars as $scholar)
                <div class="scholar-card">
                    <!-- صورة العالم -->
                    <div class="scholar-avatar">
                        @if($scholar->avatar)
                            <img src="{{ asset('storage/' . $scholar->avatar) }}" alt="{{ $scholar->name }}">
                        @elseif($scholar->image)
                            <img src="{{ asset('storage/' . $scholar->image) }}" alt="{{ $scholar->name }}">
                        @else
                            <i class="fas fa-user-graduate"></i>
                        @endif
                    </div>

                    <!-- معلومات العالم -->
                    <div class="scholar-info">
                        <!-- السطر الأول: اسم العالم -->
                        <h3 class="scholar-name">{{ $scholar->name }}</h3>

                        <!-- السطر الثاني: الصفة والوصف -->
                        <div class="scholar-description">
                            <p class="scholar-title">{{ $scholar->title ?? 'عالم' }}</p>
                            @if($scholar->bio)
                                <p class="scholar-bio">{{ Str::limit($scholar->bio, 120) }}</p>
                            @endif
                        </div>

                        <!-- السطر الثالث: الإحصائيات -->
                        <div class="scholar-stats">
                            <div class="stat-item">
                                <i class="fas fa-book-open"></i>
                                <span>{{ $scholar->sermons_count ?? 0 }} خطبة</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-question-circle"></i>
                                <span>{{ $scholar->fatwas_count ?? 0 }} فتوى</span>
                            </div>
                        </div>
                    </div>

                    <!-- زر عرض الملف الشخصي -->
                    <div class="scholar-card-footer">
                        <a href="{{ route('scholars.show', $scholar->id) }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-circle me-2"></i>
                            عرض الملف الشخصي
                            <i class="fas fa-arrow-left ms-2"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="fas fa-user-graduate fa-4x text-muted"></i>
                        </div>
                        <h3 class="text-muted">لا يوجد علماء متاحون حالياً</h3>
                        <p class="text-muted">سيتم إضافة المزيد من العلماء قريباً إن شاء الله</p>
                    </div>
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
