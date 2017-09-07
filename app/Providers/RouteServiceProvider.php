<?php

namespace App\Providers;

use DB;
use Cache;
use Schema;
use Redirect;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        $this->mapApiRoutes($router);

        $this->mapDBRoutes($router);
    }

    protected function mapDBRoutes($router)
    {
        $urls = Cache::tags(['route-seo-url'])->get('redirect.urls', function(){
            $c = [];
            if(Schema::hasTable('redirect_urls')){
                $c = DB::table('redirect_urls')->where(['is_actived' => 1])->get();
            }
            return $c;
        });
        foreach($urls as $url){
            $router->get($url->old, function()use($url){
                return Redirect::to
                    ($url->new, empty($url->code) ? 301 : $url->code);
            });
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
            require app_path('Http/Routes/admin.php');
            require app_path('Http/Routes/teach.php');
            //require app_path('Http/Routes/visit.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes all receive ip test.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapApiRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace
        ], function ($router) {
            require app_path('Http/Routes/api.php');
        });
    }
}
