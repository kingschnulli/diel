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
}
