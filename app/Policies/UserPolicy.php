<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function seeEmail(User $user, User $model)
    {
        return $user->id === $model->id || $user->admin;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $editUser
     * @return mixed
     */
    public function view(User $user, User $editUser)
    {
        return $user->admin || $editUser->user_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return !$user || $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $editUser
     * @return mixed
     */
    public function update(User $user, User $editUser)
    {
        return $user->admin || $editUser->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $editUser
     * @return mixed
     */
    public function delete(User $user, User $editUser)
    {
        return $user->admin || $editUser->user_id == $user->id;
    }
}
