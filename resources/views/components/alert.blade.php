@props(['type' => 'info', 'dismissible' => true, 'icon' => true])

@php
    $types = [
        'success' => ['class' => 'alert-success', 'icon' => 'fa-check-circle'],
        'error' => ['class' => 'alert-danger', 'icon' => 'fa-exclamation-circle'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'fa-exclamation-triangle'],
        'info' => ['class' => 'alert-info', 'icon' => 'fa-info-circle'],
    ];
    
    $config = $types[$type] ?? $types['info'];
@endphp

<div class="alert {{ $config['class'] }} {{ $dismissible ? 'alert-dismissible fade show' : '' }}" role="alert" {{ $attributes }}>
    @if($icon)
        <i class="fas {{ $config['icon'] }} me-2"></i>
    @endif
    
    {{ $slot }}
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

<style>
.alert {
    border-radius: 8px;
    border: none;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
}

.alert-warning {
    background: #fff3cd;
    color: #856404;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
}

.alert i {
    font-size: 1.1em;
}
</style>

