<?php

namespace App\Policies;

use App\Models\Kid;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KidPolicy
{
    use HandlesAuthorization;

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
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function view(User $user, Kid $kid)
    {
        return $user->belongsToTeam($kid->team) || $user->admin;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function update(User $user, Kid $kid)
    {
        return $user->ownsTeam($kid->team) || $user->admin;
    }

    /**
     * Determine whether the user can add team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function addKid(User $user, Kid $kid)
    {
        return $user->ownsTeam($kid->team) || $user->admin;
    }

    /**
     * Determine whether the user can update team member permissions.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function updateKid(User $user, Kid $kid)
    {
        return $user->ownsTeam($kid->team) || $user->admin;
    }

    /**
     * Determine whether the user can remove team members.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function removeKid(User $user, Kid $kid)
    {
        return $user->ownsTeam($kid->team) || $user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Kid  $kid
     * @return mixed
     */
    public function delete(User $user, Kid $kid)
    {
        return $user->ownsTeam($kid->team) || $user->admin;
    }
}
