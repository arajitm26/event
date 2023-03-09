<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        // Gate::define('check_permissions', function ($user,$permission,$id) {
        // return $id =="5";
        // });
        // Gate::define('check_permissions', function ($user,$permission,$id=null) {
        // return $id == null;
        // });

        Gate::define('check_permissions', 'App\Policies\RolePolicy@check_role');
        
    }
}
