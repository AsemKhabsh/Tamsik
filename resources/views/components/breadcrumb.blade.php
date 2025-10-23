@props(['items'])

<nav aria-label="breadcrumb" class="breadcrumb-nav mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">
                <i class="fas fa-home me-1"></i>
                الرئيسية
            </a>
        </li>
        @foreach($items as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['title'] }}
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

<style>
.breadcrumb-nav {
    background: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.breadcrumb {
    margin: 0;
    background: transparent;
    padding: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "←";
    color: #999;
}

.breadcrumb-item a {
    color: #1d8a4e;
    text-decoration: none;
    transition: color 0.3s;
}

.breadcrumb-item a:hover {
    color: #0f7346;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #666;
}
</style>

