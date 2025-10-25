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

        // المؤلف والمشرفون والعلماء يمكنهم عرض الخطب غير المنشورة
        if ($user) {
            return $user->id === $sermon->author_id ||
                   $user->hasAnyRole(['admin', 'scholar']);
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // فقط المشرفون والعلماء والخطباء ومدخلي البيانات يمكنهم إنشاء خطب
        return $user->hasAnyRole(['admin', 'scholar', 'preacher', 'data_entry']) ||
               $user->can('create_sermons');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sermon $sermon): bool
    {
        // المؤلف أو المشرفون والعلماء يمكنهم تعديل الخطبة
        return $user->id === $sermon->author_id ||
               $user->hasAnyRole(['admin', 'scholar']) ||
               $user->can('edit_sermons');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sermon $sermon): bool
    {
        // المؤلف أو المشرفون يمكنهم حذف الخطبة
        return $user->id === $sermon->author_id ||
               $user->hasRole('admin') ||
               $user->can('delete_sermons');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sermon $sermon): bool
    {
        // فقط المشرفون يمكنهم استعادة الخطب المحذوفة
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sermon $sermon): bool
    {
        // فقط المشرفون يمكنهم الحذف النهائي
        return $user->hasRole('admin');
    }
}
