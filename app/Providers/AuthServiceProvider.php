<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Src\Auth\Providers\UserProvider as SrcUserProvider;

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
        $this->registerProviders();
    }

    /**
     * Register user providers
     */
    protected function registerProviders()
    {
        Auth::provider('gsc', function($app, array $config) {
            return new SrcUserProvider($app['hash'], $config['model']);
        });
    }
}
