<?php

namespace App\Providers;

use App\Models\Interest;
use App\Models\Kid;
use App\Models\Participation;
use App\Models\Team;
use App\Models\User;
use App\Policies\EventGroupPolicy;
use App\Policies\EventPolicy;
use App\Policies\InterestPolicy;
use App\Policies\KidPolicy;
use App\Policies\ParticipationPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Team::class => TeamPolicy::class,
        User::class => UserPolicy::class,
        Interest::class => InterestPolicy::class,
        Event::class => EventPolicy::class,
        EventGroup::class => EventGroupPolicy::class,
        Participation::class => ParticipationPolicy::class,
        Kid::class => KidPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
