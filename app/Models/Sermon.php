<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Rating;
use App\Models\Like;
use App\Models\Favorite;

class Sermon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'sermon_date',
        'occasion',
        'content',
        'introduction',
        'main_content',
        'conclusion',
        'category',
        'tags',
        'author_id',
        'scholar_id',
        'is_published',
        'is_featured',
        'status',
        'views_count',
        'downloads_count',
        'likes_count',
        'image',
        'audio_file',
        'video_file',
        'duration',
        'difficulty_level',
        'target_audience',
        'references',
        'metadata',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'likes_count' => 'integer',
        'tags' => 'array',
        'references' => 'array',
        'metadata' => 'array',
        'sermon_date' => 'date',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * العلاقات
     */
    
    // المؤلف (المستخدم الذي كتب الخطبة)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // العالم المراجع (إذا كان مختلفاً عن المؤلف)
    public function scholar()
    {
        return $this->belongsTo(User::class, 'scholar_id');
    }

    // التعليقات على الخطبة (معطلة مؤقتاً)
    // public function comments()
    // {
    //     return $this->morphMany(Comment::class, 'commentable');
    // }

    // التقييمات
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'ratable');
    }

    // المفضلات
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    // الإعجابات
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * النطاقات (Scopes)
     */
    
    // الخطب المنشورة فقط
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // الخطب المميزة
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // البحث في الخطب
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%")
              ->orWhere('tags', 'like', "%{$term}%");
        });
    }

    // تصفية حسب التصنيف
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * الدوال المساعدة
     */
    
    // الحصول على متوسط التقييم
    public function getAverageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    // الحصول على عدد التقييمات
    public function getRatingsCount()
    {
        return $this->ratings()->count();
    }

    // زيادة عدد المشاهدات
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    // زيادة عدد التحميلات
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    // الحصول على رابط الصورة
    public function getImageUrl()
    {
        return $this->image ? asset('storage/sermons/' . $this->image) : asset('images/default-sermon.jpg');
    }

    // الحصول على رابط الملف الصوتي
    public function getAudioUrl()
    {
        return $this->audio_file ? asset('storage/sermons/audio/' . $this->audio_file) : null;
    }

    // الحصول على رابط الملف المرئي
    public function getVideoUrl()
    {
        return $this->video_file ? asset('storage/sermons/video/' . $this->video_file) : null;
    }

    // الحصول على ملخص المحتوى
    public function getExcerpt($length = 150)
    {
        return str_limit(strip_tags($this->content), $length);
    }

    // الحصول على وقت القراءة المقدر
    public function getReadingTime()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // متوسط 200 كلمة في الدقيقة
        return $minutes . ' دقيقة';
    }

    // التحقق من إعجاب المستخدم
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    // تبديل الإعجاب
    public function toggleLike(User $user)
    {
        $like = $this->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return false;
        } else {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('likes_count');
            return true;
        }
    }

    // التحقق من إضافة للمفضلة
    public function isFavoritedBy(User $user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    // تبديل المفضلة
    public function toggleFavorite(User $user)
    {
        $favorite = $this->favorites()->where('user_id', $user->id)->first();

        if ($favorite) {
            $favorite->delete();
            return false;
        } else {
            $this->favorites()->create(['user_id' => $user->id]);
            return true;
        }
    }
}
