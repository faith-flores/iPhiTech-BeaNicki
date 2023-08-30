<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;

class SkillPolicy
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
     */
    public function viewAny(User $authUser)
    {
        return
            $authUser->can('auth.skills.manage') ||
            $authUser->can('auth.skills.read');
    }

    /**
     * Determine whether the user can browse any users.
     */
    public function create(User $authUser)
    {
        return
            $authUser->can('auth.skills.manage') ||
            $authUser->can('auth.skills.create');
    }

    /**
     * Determine whether the user can browse a user.
     *
     *
     * @return bool
     */
    public function read(User $authUser, User $user)
    {
        return
            $authUser->can('auth.skills.read') ||
            $authUser->getKey() === $user->getKey();
    }

    /**
     * Determine whether the user can edit a user.
     *
     *
     * @return bool
     */
    public function edit(User $authUser, User $user)
    {
        return
            $authUser->can('auth.skills.manage') ||
            $authUser->getKey() === $user->getKey();
    }

    /**
     * Determine whether the user can add user.
     */
    public function add(User $authUser)
    {
        return $authUser->can('auth.skills.manage');
    }
}
