@props(['url' => '', 'title' => '', 'description' => ''])

@php
    $shareUrl = $url ?: url()->current();
    $shareTitle = $title ?: config('app.name');
    $shareDescription = $description ?: '';
@endphp

<div class="share-buttons" {{ $attributes }}>
    <h6 class="share-title mb-3">
        <i class="fas fa-share-alt me-2"></i>
        شارك هذا المحتوى:
    </h6>
    
    <div class="share-buttons-group">
        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
           target="_blank" 
           class="share-btn share-facebook"
           title="شارك على فيسبوك">
            <i class="fab fa-facebook-f"></i>
        </a>
        
        {{-- Twitter --}}
        <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}" 
           target="_blank" 
           class="share-btn share-twitter"
           title="شارك على تويتر">
            <i class="fab fa-twitter"></i>
        </a>
        
        {{-- WhatsApp --}}
        <a href="https://wa.me/?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}" 
           target="_blank" 
           class="share-btn share-whatsapp"
           title="شارك على واتساب">
            <i class="fab fa-whatsapp"></i>
        </a>
        
        {{-- Telegram --}}
        <a href="https://t.me/share/url?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}" 
           target="_blank" 
           class="share-btn share-telegram"
           title="شارك على تيليجرام">
            <i class="fab fa-telegram-plane"></i>
        </a>
        
        {{-- Copy Link --}}
        <button type="button" 
                class="share-btn share-copy" 
                onclick="copyToClipboard('{{ $shareUrl }}')"
                title="نسخ الرابط">
            <i class="fas fa-link"></i>
        </button>
    </div>
</div>

<style>
.share-buttons {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.share-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 15px;
}

.share-buttons-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.share-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.share-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.share-facebook {
    background: #1877f2;
}

.share-twitter {
    background: #1da1f2;
}

.share-whatsapp {
    background: #25d366;
}

.share-telegram {
    background: #0088cc;
}

.share-copy {
    background: #6c757d;
}

.share-btn i {
    font-size: 1.1rem;
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('تم نسخ الرابط بنجاح!');
    }, function(err) {
        console.error('فشل نسخ الرابط: ', err);
    });
}
</script>

