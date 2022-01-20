<?php

namespace App\Providers;

use App\Models\Interest;
use App\Models\Team;
use App\Policies\EventGroupPolicy;
use App\Policies\EventPolicy;
use App\Policies\InterestPolicy;
use App\Policies\TeamPolicy;
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
        Interest::class => InterestPolicy::class,
        Event::class => EventPolicy::class,
        EventGroup::class => EventGroupPolicy::class
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
