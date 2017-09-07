<?php

namespace App\Providers;

use Schema;
use Cache;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if(Schema::hasTable('settings')){
            $setingsCache = Cache::tags(['settings'])->rememberForever('setingsCache',function() {
                return \App\Setting::lists('value', 'name')->all();
            });
            //dd(Cache::tags(['settings'])->get('setingsCache'));
            config()->set('settings', $setingsCache);
        }
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
