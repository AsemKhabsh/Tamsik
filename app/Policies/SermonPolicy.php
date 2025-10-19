<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Sermon;
use App\Models\User;

class SermonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // الجميع يمكنهم عرض قائمة الخطب
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Sermon $sermon): bool
    {
        // الجميع يمكنهم عرض الخطب المنشورة
        if ($sermon->is_published) {
            return true;
        }

        // المؤلف والمشرفون يمكنهم عرض الخطب غير المنشورة
        if ($user) {
            return $user->id === $sermon->author_id ||
                   in_array($user->user_type, ['admin', 'scholar']);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // فقط المشرفون والعلماء والخطباء يمكنهم إنشاء خطب
        return in_array($user->user_type, ['admin', 'scholar', 'preacher']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sermon $sermon): bool
    {
        // المؤلف أو المشرفون يمكنهم تعديل الخطبة
        return $user->id === $sermon->author_id ||
               in_array($user->user_type, ['admin', 'scholar']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sermon $sermon): bool
    {
        // المؤلف أو المشرفون يمكنهم حذف الخطبة
        return $user->id === $sermon->author_id ||
               $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sermon $sermon): bool
    {
        // فقط المشرفون يمكنهم استعادة الخطب المحذوفة
        return $user->user_type === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sermon $sermon): bool
    {
        // فقط المشرفون يمكنهم الحذف النهائي
        return $user->user_type === 'admin';
    }
}
