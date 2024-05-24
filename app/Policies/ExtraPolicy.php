<?php

namespace App\Policies;

use App\Models\Extra;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ExtraPolicy
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
        $allowedRoles = ['Admin', 'Manager', 'User'];
        foreach ($user->roles() as $role) {
            if (in_array($role->name, $allowedRoles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Extra $extra
     * @return Response|bool
     */
    public function view(User $user, Extra $extra)
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
        $allowedRoles = ['Admin', 'Manager', 'User'];
        foreach ($user->roles() as $role) {
            if (in_array($role->name, $allowedRoles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Extra $extra
     * @return Response|bool
     */
    public function update(User $user, Extra $extra)
    {
        return $user->roles->name == 'Admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Extra $extra
     * @return Response|bool
     */
    public function delete(User $user, Extra $extra)
    {
        return $user->roles->name == 'Admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Extra $extra
     * @return Response|bool
     */
    public function restore(User $user, Extra $extra)
    {
        return $user->roles->name == 'Admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Extra $extra
     * @return Response|bool
     */
    public function forceDelete(User $user, Extra $extra)
    {
        return $user->roles->name == 'Admin';
    }
}
