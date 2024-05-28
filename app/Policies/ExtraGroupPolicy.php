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
        return in_array($user->roles->name, ['Admin', 'Manager', 'User']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function view(User $user, ExtraGroup $extraGroup)
    {
        return in_array($user->roles->name, ['Admin', 'Manager', 'User']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return in_array($user->roles->name, ['Admin']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function update(User $user, ExtraGroup $extraGroup)
    {
        return in_array($user->roles->name, ['Admin', 'Manager']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function delete(User $user, ExtraGroup $extraGroup)
    {
        return in_array($user->roles->name, ['Admin']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function restore(User $user, ExtraGroup $extraGroup)
    {
        return in_array($user->roles->name, ['Admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param ExtraGroup $extraGroup
     * @return Response|bool
     */
    public function forceDelete(User $user, ExtraGroup $extraGroup)
    {
        return in_array($user->roles->name, ['Admin']);
    }
}
