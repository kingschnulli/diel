<?php

namespace App\Policies;

use App\Models\EventGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventGroupPolicy
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
     * @param  \App\Models\EventGroup  $eventGroup
     * @return mixed
     */
    public function view(User $user, EventGroup $eventGroup)
    {
        return true;
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
     * @param  \App\Models\EventGroup $eventGroup
     * @return mixed
     */
    public function update(User $user, EventGroup $eventGroup)
    {
        return $user->admin;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventGroup $eventGroup
     * @return mixed
     */
    public function delete($user, EventGroup $eventGroup)
    {
        return $user->admin;
    }

    /**
     * Determine whether the user can participate in event
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EventGroup  $eventGroup
     * @return mixed
     */
    public function participate($user, EventGroup $eventGroup)
    {
        return true;
    }
}