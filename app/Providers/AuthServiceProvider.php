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

        // صلاحيات الخطب
        Gate::define('create_sermons', fn($user) => $user->hasPermissionTo('create_sermons'));
        Gate::define('edit_sermons', fn($user) => $user->hasPermissionTo('edit_sermons'));
        Gate::define('delete_sermons', fn($user) => $user->hasPermissionTo('delete_sermons'));
        Gate::define('publish_sermons', fn($user) => $user->hasPermissionTo('publish_sermons'));

        // صلاحيات المحاضرات
        Gate::define('create_lectures', fn($user) => $user->hasPermissionTo('create_lectures'));
        Gate::define('edit_lectures', fn($user) => $user->hasPermissionTo('edit_lectures'));
        Gate::define('delete_lectures', fn($user) => $user->hasPermissionTo('delete_lectures'));
        Gate::define('publish_lectures', fn($user) => $user->hasPermissionTo('publish_lectures'));

        // صلاحيات الفتاوى
        Gate::define('create_fatwas', fn($user) => $user->hasPermissionTo('create_fatwas'));
        Gate::define('edit_fatwas', fn($user) => $user->hasPermissionTo('edit_fatwas'));
        Gate::define('delete_fatwas', fn($user) => $user->hasPermissionTo('delete_fatwas'));
        Gate::define('publish_fatwas', fn($user) => $user->hasPermissionTo('publish_fatwas'));

        // صلاحيات المقالات
        Gate::define('create_articles', fn($user) => $user->hasPermissionTo('create_articles'));
        Gate::define('edit_articles', fn($user) => $user->hasPermissionTo('edit_articles'));
        Gate::define('delete_articles', fn($user) => $user->hasPermissionTo('delete_articles'));
        Gate::define('publish_articles', fn($user) => $user->hasPermissionTo('publish_articles'));

        // صلاحيات التعليقات
        Gate::define('comment_on_articles', fn($user) => $user->hasPermissionTo('comment_on_articles'));
        Gate::define('reply_to_comments', fn($user) => $user->hasPermissionTo('reply_to_comments'));
        Gate::define('moderate_comments', fn($user) => $user->hasPermissionTo('moderate_comments'));

        // صلاحيات التفاعل
        Gate::define('view_content', fn($user) => $user->hasPermissionTo('view_content'));
        Gate::define('like_content', fn($user) => $user->hasPermissionTo('like_content'));
        Gate::define('rate_content', fn($user) => $user->hasPermissionTo('rate_content'));
        Gate::define('ask_scholars', fn($user) => $user->hasPermissionTo('ask_scholars'));

        // صلاحيات الإدارة
        Gate::define('manage_users', fn($user) => $user->hasPermissionTo('manage_users'));
        Gate::define('manage_content', fn($user) => $user->hasPermissionTo('manage_content'));
        Gate::define('view_admin_panel', fn($user) => $user->hasPermissionTo('view_admin_panel'));
        Gate::define('access_admin', fn($user) => $user->hasRole('admin'));
    }
}
