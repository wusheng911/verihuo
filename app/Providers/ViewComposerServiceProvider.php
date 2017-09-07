<?php

namespace App\Providers;

use App\Http\Controllers\HomeController;
use App\Models\Content;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

use Log;
use View;
use Cache;
use Auth;
use App\Models\ContentTag;
use App\Services\ApiClient;
use App\Models\FriendLink;
use App\Models\ContentCategory;
use App\Models\AdPosition;
use App\Http\Controllers\Api\CategoryController;


class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

		View::composer('mobile.elements.offcanvas', function($view)
		{
			$news_categories = [];
			
			if (!Cache::has('news_categories')) {
				$api_client = new ApiClient();
				if ($api_client->get('/api/categories')) {
					$news_categories = $api_client->getBody();
				}                
				
				Cache::put('news_categories', $news_categories, 7*24*60);
				
			} else {
				Log::info('Cache Hit: news_categories');
				$news_categories = Cache::get('news_categories');
			}
			
			$view->with('news_categories', $news_categories);
		});
		
		
		View::composer('mobile.elements.shopmenu', function($view) 
		{   
			$shop_categories = [];
			
			if (!Cache::has('shop_categories')) {
				$api_client = new ApiClient();
				if ($api_client->get('/api/shopcategories')) {
					$shop_categories = $api_client->getBody();
				}                
				
				Cache::put('shop_categories', $shop_categories, 7*24*60);
				
			} else {
				Log::info('Cache Hit: shop_categories');
				$shop_categories = Cache::get('shop_categories');
			}
			
			$view->with('shop_categories', $shop_categories);            
		});

		//pop bottom composer for pc
		view()->composer(explode(',',config('settings.popbottom_views')),
						 'App\Http\ViewComposers\PopbottomComposer');

        //友情链接
		View::composer('elements.frontend.headerbase', function($view) {
			
        $aboutMe = [];
		$teacherInfo =[];
        $ad = AdPosition::getAdPositions("PC|Home|AboutMe");
        $aboutMe = json_decode(json_encode($ad), true);
        $ad = AdPosition::getAdPositions("PC|Home|TeacherInfo");
        $teacherInfo = json_decode(json_encode($ad), true);
            $view->with(['aboutMe'=> $aboutMe,'teacherInfo'=>$teacherInfo]);
        });

		//后台登录用户退出
		View::composer('layouts.admin', function($view) {
			$user = Auth::guard('admin')->user();
			$view->with('user', $user);
		});

        ////二级导航栏
		View::composer('elements.frontend.shopnavigator', 'App\Http\ViewComposers\ShopNavComposer');
		View::composer(['pc.home','pc.tagsearch', 'pc.contentcategory.news', 'pc.article'],
					   'App\Http\ViewComposers\ContentNavComposer');
	View::composer('pc.shop.*','App\Http\ViewComposers\ShopBarComposer');
        /*
        View::composer('*', function($view)
        {
            if (Session::has('promptMessage')) {
                $prompt_type = Session::get('promptMessage.message_type');
                $prompt_message = Session::get('promptMessage.message');
            }
                      
            $view->with(['prompt_type'=>$prompt_type, 'prompt_message' => $prompt_message ]);
        });
         */        
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
