<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can browse any users.
     *
     * @param  User $authUser
     * @return mixed
     */
    public function viewAny(User $authUser)
    {
        return (
            $authUser->can('auth.permissions.manage') ||
            $authUser->can('auth.permissions.read')
        );
    }

    /**
     * Determine whether the user can browse any users.
     *
     * @param  User $authUser
     * @return mixed
     */
    public function create(User $authUser)
    {
        return (
            $authUser->can('auth.permissions.manage') ||
            $authUser->can('auth.permissions.create')
        );
    }

    /**
     * Determine whether the user can browse a user.
     *
     * @param User $authUser
     * @param User $user
     *
     * @return bool
     */
    public function read(User $authUser, User $user)
    {
        return (
            $authUser->can('auth.permissions.read') ||
            $authUser->getKey() === $user->getKey()
        );
    }

    /**
     * Determine whether the user can edit a user.
     *
     * @param User $authUser
     * @param User $user
     *
     * @return bool
     */
    public function edit(User $authUser, User $user)
    {
        return (
            $authUser->can('auth.permissions.manage') ||
            $authUser->getKey() === $user->getKey()
        );
    }

    /**
     * Determine whether the user can add user.
     *
     * @param  User $authUser
     * @return mixed
     */
    public function add(User $authUser)
    {
        return $authUser->can('auth.permissions.manage');
    }
}
