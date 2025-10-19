<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fatwa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'question',
        'answer',
        'category',
        'tags',
        'questioner_id',
        'scholar_id',
        'is_published',
        'is_featured',
        'views_count',
        'priority',
        'references',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'references' => 'array'
    ];

    protected $dates = [
        'published_at',
        'deleted_at'
    ];

    // العلاقات
    public function scholar()
    {
        return $this->belongsTo(User::class, 'scholar_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // النطاقات (Scopes)
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopePending($query)
    {
        return $query->where('is_published', false);
    }

    public function scopeAnswered($query)
    {
        return $query->whereNotNull('answer')
                    ->where('answer', '!=', '');
    }

    public function scopeUnanswered($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('answer')
              ->orWhere('answer', '');
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('question', 'like', "%{$term}%")
              ->orWhere('answer', 'like', "%{$term}%");
        });
    }

    public function scopeByScholar($query, $scholarId)
    {
        return $query->where('scholar_id', $scholarId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    // الخصائص المحسوبة
    public function getIsPublishedStatusAttribute()
    {
        return $this->is_published && $this->published_at <= now();
    }

    public function getIsAnsweredAttribute()
    {
        return !empty($this->answer);
    }

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y') : null;
    }

    public function getShortQuestionAttribute()
    {
        return \Str::limit($this->question, 100);
    }

    public function getShortAnswerAttribute()
    {
        return \Str::limit($this->answer, 200);
    }

    public function getDifficultyLevelTextAttribute()
    {
        $levels = [
            'beginner' => 'مبتدئ',
            'intermediate' => 'متوسط',
            'advanced' => 'متقدم'
        ];

        return $levels[$this->difficulty_level] ?? 'غير محدد';
    }

    // الطرق
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isFavoritedBy(User $user)
    {
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

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

    public function markAsAnswered($answer, $scholarId = null)
    {
        $this->update([
            'answer' => $answer,
            'scholar_id' => $scholarId ?: $this->scholar_id,
        ]);
    }

    public function publish()
    {
        $this->update([
            'is_published' => true,
            'published_at' => now()
        ]);
    }
}
