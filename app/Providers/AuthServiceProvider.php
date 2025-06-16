<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Register your model policies here
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define the 'admin' ability
        Gate::define('admin', function (User $user) {
            return $user->usertype === 'admin'; // Adjust based on your user type logic
        });

         // Gate for user
        Gate::define('user', function ($user) {
            return $user->usertype === 'user';
        });
    }
}
