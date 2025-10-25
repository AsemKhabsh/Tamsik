@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-bell me-2" style="color: #1d8a4e;"></i>
                    الإشعارات
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                    @endif
                </h2>
                
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-check-double me-1"></i>
                            تحديد الكل كمقروء
                        </button>
                    </form>
                @endif
            </div>

            <!-- Notifications List -->
            @if($notifications->count() > 0)
                <div class="card shadow-sm">
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <!-- Notification Icon -->
                                        <div class="d-flex align-items-start mb-2">
                                            <div class="notification-icon me-3">
                                                @if($notification->type === 'App\Notifications\FatwaAnsweredNotification')
                                                    <i class="fas fa-comment-dots fa-2x" style="color: #1d8a4e;"></i>
                                                @else
                                                    <i class="fas fa-bell fa-2x" style="color: #1d8a4e;"></i>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <!-- Notification Message -->
                                                <h6 class="mb-1">
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-success me-2">جديد</span>
                                                    @endif
                                                    {{ $notification->data['message'] ?? 'إشعار جديد' }}
                                                </h6>
                                                
                                                <!-- Notification Details -->
                                                @if(isset($notification->data['scholar_name']))
                                                    <p class="mb-1 text-muted small">
                                                        <i class="fas fa-user-graduate me-1"></i>
                                                        العالم: {{ $notification->data['scholar_name'] }}
                                                    </p>
                                                @endif
                                                
                                                <!-- Notification Time -->
                                                <p class="mb-0 text-muted small">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="mt-2">
                                            @if(isset($notification->data['url']))
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">
                                                        <i class="fas fa-eye me-1"></i>
                                                        عرض
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-check me-1"></i>
                                                        تحديد كمقروء
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                                                    <i class="fas fa-trash me-1"></i>
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد إشعارات</h4>
                        <p class="text-muted">سيتم إعلامك هنا عند وجود تحديثات جديدة</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.notification-icon {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 50%;
}

.list-group-item {
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa !important;
}

.bg-light {
    background-color: #e8f5e9 !important;
}
</style>
@endsection

