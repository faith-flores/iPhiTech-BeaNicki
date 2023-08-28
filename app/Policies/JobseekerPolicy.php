<?php

namespace App\Policies;

use App\Models\Jobseeker;
use App\Models\User;

class JobseekerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('jobseekers.manage') || $user->can('jobseekers.read') || $user->can('jobseekers.read.own');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Jobseeker $jobseeker): bool
    {
        return (
            $user->getKey() === $jobseeker->user_id || (
                $user->can('jobseekers.read') ||
                $user->can('jobseekers.manage')
            )
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('jobseekers.manage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Jobseeker $jobseeker): bool
    {
        return $jobseeker->user_id === $user->getKey() || $user->can('jobseekers.manage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Jobseeker $jobseeker): bool
    {
        return $user->can('jobseekers.manage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Jobseeker $jobseeker): bool
    {
        return $user->can('jobseekers.manage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Jobseeker $jobseeker): bool
    {
        return $user->can('jobseekers.manage');
    }
}
