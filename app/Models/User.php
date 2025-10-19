<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'bio',
        'specialization',
        'location',
        'is_active',
        'avatar',
        'phone',
        'join_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'join_date' => 'date'
    ];

    /**
     * العلاقات
     */
    
    // الخطب التي كتبها المستخدم
    public function sermons()
    {
        return $this->hasMany(Sermon::class, 'author_id');
    }

    // المقالات التي كتبها المستخدم
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    // المحاضرات التي ألقاها المستخدم
    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'speaker_id');
    }

    // الفتاوى التي أجاب عليها العالم
    public function fatwas()
    {
        return $this->hasMany(Fatwa::class, 'scholar_id');
    }

    // التعليقات التي كتبها المستخدم
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // التقييمات التي أعطاها المستخدم
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * الدوال المساعدة
     */
    
    // التحقق من كون المستخدم عالماً
    public function isScholar()
    {
        return $this->hasRole('scholar');
    }

    // التحقق من كون المستخدم مشرفاً
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    // التحقق من كون المستخدم خطيباً
    public function isMember()
    {
        return $this->hasRole('member');
    }

    // الحصول على اسم الدور
    public function getRoleName()
    {
        $roleNames = [
            'admin' => 'مشرف المنصة',
            'scholar' => 'عالم',
            'member' => 'خطيب',
            'guest' => 'زائر'
        ];

        $role = $this->roles->first();
        return $role ? ($roleNames[$role->name] ?? $role->name) : 'زائر';
    }

    // الحصول على الصورة الشخصية
    public function getAvatarUrl()
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('images/default-avatar.png');
    }
}
