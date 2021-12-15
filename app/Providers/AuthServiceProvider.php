<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('tours.edit', function ($user,$tour) {
            return $user->affiliateId == $tour->affiliateId;
        });
        Gate::define('users', function ($user) {
            return $user->affiliateId==1;
            // return $user->role == User::ROLE_ADMIN;
        });
        Gate::define('affiliates', function ($user) {
            return $user->affiliateId==1;
            // return $user->role == User::ROLE_ADMIN;
        });
    }
    
}
