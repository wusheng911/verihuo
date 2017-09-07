<?php

namespace App\Providers;

use Auth;
use App\Customer;
use App\Auth\CustomProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class CustomAuthServiceProvider extends ServiceProvider
{

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        Auth::provider('custom', function($app, array $config) {
            return new CustomProvider($app->make('hash'), 'App\Customer');
        });

    }
}