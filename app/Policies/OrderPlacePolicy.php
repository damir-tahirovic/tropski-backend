<?php

namespace App\Policies;

use App\Models\OrderPlace;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPlacePolicy
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
     * @param OrderPlace $orderPlace
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
     * @param OrderPlace $orderPlace
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
     * @param OrderPlace $orderPlace
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
     * @param OrderPlace $orderPlace
     * @return Response|bool
     */
    public function restore(User $user, OrderPlace $orderPlace)
    {
        // Add your logic here
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param OrderPlace $orderPlace
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
