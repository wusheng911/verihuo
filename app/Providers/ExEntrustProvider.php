<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ExEntrustProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        // Register blade directives
        $this->bladeDirectives();
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

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('entrust.php'),
        ]);
        
        // Call to Entrust::hasRole
        \Blade::directive('role', function($expression) {
            $guard = \Config::get('entrust.exguard');
            return "<?php if (\\Auth::guard('{$guard}')->user()->hasRole{$expression}) : ?>";
        });

        \Blade::directive('endrole', function($expression) {
            return "<?php endif; // Entrust::hasRole ?>";
        });

        // Call to Entrust::can
        \Blade::directive('permission', function($expression) {
            $guard = \Config::get('entrust.exguard');
            return "<?php if (\\Auth::guard('{$guard}')->user()->can{$expression}) : ?>";
        });

        \Blade::directive('endpermission', function($expression) {
            return "<?php endif; // Entrust::can ?>";
        });

        // Call to Entrust::ability
        \Blade::directive('ability', function($expression) {
            $guard = \Config::get('entrust.exguard');
            return "<?php if (\\Auth::guard('{$guard}')->user()->ability{$expression}) : ?>";
        });

        \Blade::directive('endability', function($expression) {
            return "<?php endif; // Entrust::ability ?>";
        });
        
        //
        \Blade::directive('nav', function($expression) {
        });
        
        \Blade::directive('endnav', function($expression) {
        });
    }
}
