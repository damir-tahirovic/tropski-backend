<?php

namespace App\Policies;

use App\Models\ExtraGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ExtraGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin', 'Manager'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function view(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin', 'Manager'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function update(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin', 'Manager'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function delete(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function restore(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function forceDelete(User $user)
    {
        foreach ($user->roles() as $role) {
            if (in_array($role->name, ['Admin'])) {
                return true;
            }
        }
        return false;
    }
}
