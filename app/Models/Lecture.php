<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'topic',
        'category',
        'speaker_id',
        'location',
        'city',
        'venue',
        'scheduled_at',
        'duration',
        'is_published',
        'is_recurring',
        'recurrence_pattern',
        'max_attendees',
        'registered_count',
        'contact_phone',
        'contact_email',
        'tags',
        'requirements',
        'target_audience',
        'status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_published' => 'boolean',
        'is_recurring' => 'boolean',
        'tags' => 'array'
    ];

    protected $dates = [
        'scheduled_at',
        'deleted_at'
    ];

    // العلاقات
    public function speaker()
    {
        return $this->belongsTo(User::class, 'speaker_id');
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'lecture_attendees')
                    ->withTimestamps();
    }

    // النطاقات (Scopes)
    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_at', '>', now())
                    ->where('status', 'scheduled');
    }

    public function scopePublished($query)
    {
        return $query->whereIn('status', ['scheduled', 'completed']);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('location', 'like', "%{$term}%");
        });
    }

    // الخصائص المحسوبة
    public function getIsUpcomingAttribute()
    {
        return $this->scheduled_at > now() && $this->status === 'scheduled';
    }

    public function getIsFullAttribute()
    {
        return $this->max_attendees && $this->current_attendees >= $this->max_attendees;
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->duration) return null;
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . ' ساعة' . ($minutes > 0 ? ' و ' . $minutes . ' دقيقة' : '');
        }
        
        return $minutes . ' دقيقة';
    }

    // الطرق
    public function canUserAttend(User $user)
    {
        return $this->is_upcoming && 
               !$this->is_full && 
               !$this->attendees()->where('user_id', $user->id)->exists();
    }

    public function addAttendee(User $user)
    {
        if ($this->canUserAttend($user)) {
            $this->attendees()->attach($user->id);
            $this->increment('current_attendees');
            return true;
        }
        
        return false;
    }

    public function removeAttendee(User $user)
    {
        if ($this->attendees()->where('user_id', $user->id)->exists()) {
            $this->attendees()->detach($user->id);
            $this->decrement('current_attendees');
            return true;
        }
        
        return false;
    }
}
