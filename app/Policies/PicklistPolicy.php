<?php

namespace App\Policies;

use App\Models\Picklist;
use App\Models\PicklistItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PicklistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('settings.picklists.manage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Picklist $picklist): bool
    {
        return !!$user;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('settings.picklists.manage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Picklist $picklist): bool
    {
        return $user->can('settings.picklists.manage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Picklist $picklist): bool
    {
        return $user->can('settings.picklists.manage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Picklist $picklist): bool
    {
        return $user->can('settings.picklists.manage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Picklist $picklist): bool
    {
        return $user->can('settings.picklists.manage');
    }

    public function readItem(User $user, PicklistItem $pickListItem)
    {
        return $user->can('system.pick_lists.items.read');
    }
}
