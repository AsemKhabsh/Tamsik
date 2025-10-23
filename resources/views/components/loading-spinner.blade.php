@props(['size' => 'md', 'color' => 'primary'])

@php
    $sizeClasses = [
        'sm' => 'spinner-sm',
        'md' => 'spinner-md',
        'lg' => 'spinner-lg',
    ];
    
    $colorClasses = [
        'primary' => 'spinner-primary',
        'success' => 'spinner-success',
        'white' => 'spinner-white',
    ];
    
    $sizeClass = $sizeClasses[$size] ?? 'spinner-md';
    $colorClass = $colorClasses[$color] ?? 'spinner-primary';
@endphp

<div class="loading-spinner {{ $sizeClass }} {{ $colorClass }}" {{ $attributes }}>
    <div class="spinner-border" role="status">
        <span class="visually-hidden">جاري التحميل...</span>
    </div>
</div>

<style>
.loading-spinner {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.spinner-sm .spinner-border {
    width: 1.5rem;
    height: 1.5rem;
    border-width: 0.2em;
}

.spinner-md .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

.spinner-lg .spinner-border {
    width: 5rem;
    height: 5rem;
    border-width: 0.4em;
}

.spinner-primary .spinner-border {
    color: #1d8a4e;
}

.spinner-success .spinner-border {
    color: #28a745;
}

.spinner-white .spinner-border {
    color: #fff;
}
</style>

