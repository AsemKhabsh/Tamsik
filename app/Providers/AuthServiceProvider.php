<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Sermon::class => \App\Policies\SermonPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for permissions
        Gate::define('create_sermons', function ($user) {
            return $user->hasPermissionTo('create_sermons');
        });

        Gate::define('edit_sermons', function ($user) {
            return $user->hasPermissionTo('edit_sermons');
        });

        Gate::define('delete_sermons', function ($user) {
            return $user->hasPermissionTo('delete_sermons');
        });

        Gate::define('manage_users', function ($user) {
            return $user->hasPermissionTo('manage_users');
        });

        Gate::define('access_admin', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
