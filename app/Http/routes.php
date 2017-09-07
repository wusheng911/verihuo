<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Redirect;
use App\Services\Helpers;

Route::get('/lang/{locale}', 'LanguageController@setLocale');  

if (Helpers::isMobile()) {
    // 移动端路由
    Route::get('/shop', 'Mobile\ShopController@index');
    Route::get('/view/article/{id}', 'ViewController@article');
    Route::get('/news/{id}', 'Mobile\NewsController@news');
    Route::get('/', 'Mobile\NewsController@home');
    
    Route::get('/view/product/{id}', 'Mobile\ShopController@showProduct');
    Route::get('/shop/category/{id}', 'Mobile\ShopController@listProducts');
    Route::get('/shop/cart', 'Mobile\ShopController@showCart')->middleware('wechat.platform.oauth');
    Route::get('/shop/order', 'Mobile\ShopController@startOrder')->middleware('wechat.platform.oauth');
    Route::post('/shop/order', 'Mobile\ShopController@startOrder')->middleware('wechat.platform.oauth');
    Route::post('/shop/createorder', 'Mobile\ShopController@createOrder')->middleware('wechat.platform.oauth');
} else {
    // 桌面端路由
    Route::get('/shop','ShopController@index');
    Route::get('/view/article/{id}', 'ViewController@articleForPc')->name('view.article.id');   
    Route::get('/news/{id}', 'ContentCategoryController@news');
    Route::get('/shop/category', 'Shop\CategoryController@index');
    Route::get('/shop/productsearch/{key}', 'Shop\ProductController@search');
    Route::get('/shop/category/{id}', 'Shop\CategoryController@index');
    Route::get('/', 'HomeController@index');
    Route::get('/view/product/{id}', 'ViewController@showProduct');
    Route::get('/shop/order', 'ShopController@startOrder')->middleware('wechat.platform.oauth');
    Route::post('/shop/order', 'ShopController@startOrder')->middleware('wechat.platform.oauth');
    Route::post('/shop/createorder', 'ShopController@createOrder')->middleware('wechat.platform.oauth');
    Route::get('/shop/cart', 'ShopController@cartList');
}
Route::get('/view/artcomments/{id}', 'ViewController@artComments');  
Route::get('/getnavdata','HomeController@getnavdata');
Route::get('/content/searchbytag', ['uses'=>'ViewController@tagSearchForPc','as'=>'content.searchbytag.index']);
Route::get('/content/searchbytag/{tag}','ViewController@tagSearchForPc');
Route::get('/content/searchbytagid/{tag}','ViewController@tagIdAjax');

// pay page
Route::get('/pay/devorders', 'Pay\OrderPayController@devOrder');
Route::get('/pay/orders/{ids}', 'Pay\OrderPayController@index')->middleware('wechat.platform.oauth');
Route::get('/pay/orders/{id}/ispaid', 'Pay\OrderPayController@ajaxIsPaid');
Route::get('/pay/return/{gateway}/', 'Pay\OrderPayController@payReturn');
Route::post('/pay/notify/{gateway}/', 'Pay\OrderPayController@payNotify');

//robots
Route::get('robots.txt', 'RobotsController@index');

//wechat
Route::get('/wechat/index', 'Wechat\WechatController@index');
Route::get('/wechat/oauth_callback', 'Wechat\WechatController@oauthCallback');
Route::post('/wechat/register_step2', 'Wechat\WechatController@register_step2');
Route::any('/wechat/serve', 'Wechat\WechatController@serve');

// elfinder libs
Route::get('/elfinder/popup', '\Barryvdh\Elfinder\ElfinderController@showPopup');

// redirects
Route::get('/Articles/view/{id}', function($id){return Redirect::to('/view/article/'.$id, 301);});
Route::get('/articles/view/{id}', function($id){return Redirect::to('/view/article/'.$id, 301);});
Route::get('/news/type/{id}', function($id){return Redirect::to('/news/'.$id, 301);});

Route::get('/sitemap', 'SitemapController@render');
Route::get('/sitemap/create', 'SitemapController@store');

// ajax requests
Route::post('/addartcomment/', 'CommentController@ajaxAddComment');
Route::post('/thumbsupartcomment/', 'CommentController@ajaxThumbsupComment');
Route::get('/ajax/registeredby/{value}', 'AjaxController@registeredBy');
Route::post('/ajax/sendsms', 'AjaxController@sendSMS');
Route::post('/ajax/addtocart', 'AjaxController@addToCart');
Route::get('/ajax/getZonesForPC', 'AjaxController@getZonesForPC');
Route::post('/ajax/deleteAddress', 'AjaxController@deleteAddress');
Route::get('/ajax/getzones', 'AjaxController@getZones');
Route::post('/ajax/updateCartQuantity/{id}', 'AjaxController@updateCartProductQuantity');
Route::post('/ajax/deleteCartProduct', 'AjaxController@deleteCartProduct');
Route::get('/ajax/deleteCartProduct', 'AjaxController@deleteCartProduct');
Route::post('/ajax/updateaddress/{id}', 'AjaxController@updateAddress');
Route::post('/ajax/addaddress', 'AjaxController@addAddress');
Route::post('/ajax/deleteaddresses/{ids}', 'AjaxController@deleteAddresses');
Route::get('/ajax/iscartexist', 'AjaxController@isCartExist');

//for mobile only
Route::group(
    ['prefix' => 'm',
     'namespace' => 'Mobile'],
    function() 
    {

        Route::get('/previous', 'NewsController@previous');
        Route::get('/ajax/article/{id}','AjaxController@loadArticle');
        Route::get('/ajax/loadcomments/{id}', 'AjaxController@loadComments');        
        Route::post('/ajax/addcomment/{id}', 'AjaxController@addComment');
        Route::post('/ajax/savethumb/{id}', 'AjaxController@savethumb');
        Route::post('/ajax/updateartcomment/{id}', 'AjaxController@updateArtComment');
        Route::post('/ajax/deletecartitems/{ids}', 'AjaxController@deleteCartItems');
        Route::get('/ajax/getzones', 'AjaxController@getZones');     
        Route::post('/ajax/updateaddress/{id}', 'AjaxController@updateAddress');
        Route::post('/ajax/addaddress', 'AjaxController@addAddress');
        Route::post('/ajax/deleteaddresses/{ids}', 'AjaxController@deleteAddresses');
        Route::post('/ajax/pickaddress', 'AjaxController@pickAddress');
        Route::post('/ajax/changecartquantity/{id}', 'AjaxController@changeCartQuantity');
        Route::get('/ajax/isordercompleted', 'AjaxController@isOrderCompleted');
        
        // compatible with old portal
        Route::get('/', 'NewsController@home')->middleware('wechat.platform.oauth');
        Route::get('/news/{id}', 'NewsController@news')->middleware('wechat.platform.oauth');
        Route::get('/view/{id}', '\App\Http\Controllers\ViewController@article')->middleware('wechat.platform.oauth');
    }
);

Route::get('/members/login/channel/{oauth_type?}', 'CustomerController@oauthLogin');

Route::group(
    ['prefix' => 'customer'],
    function()
    {
        // Route::get('/login', 'CustomerController@loginForm');
        Route::post('/login', 'CustomerController@login');
        Route::get('/register', 'CustomerController@registerForm');
        // Route::post('/register', 'CustomerController@register'); 
        // Route::post('/authregister','CustomerController@oauthRegister');
        Route::get('/logout', 'CustomerController@logout');
        Route::get('/resetpassword', 'CustomerController@resetPasswordForm');
        Route::post('/resetpassword', 'CustomerController@resetPassword');
        Route::get('/isregister','CustomerController@isRegisterNumber');
        Route::post('/studentregister', 'CustomerController@studentRegister');

        Route::post('/personalinterestschool', 'CustomerController@interestSchool');
        Route::post('/personalarchive', 'CustomerController@archive');
        Route::post('/personalccscore', 'CustomerController@ccscore');
        Route::post('/personalinfo', 'CustomerController@info');
        Route::post('/personalbg', 'CustomerController@bg');
        Route::post('/personalscore', 'CustomerController@score');
        Route::post('/personalexp1', 'CustomerController@exp1');
        Route::post('/personalexp2', 'CustomerController@exp2');
        Route::get('/personal', 'CustomerController@personal');
    });

Route::group(
    ['prefix' => 'customer', 'middleware' => ['auth']],
    function()
    {

        Route::get('/account', 'CustomerController@account');
        Route::get('/info', 'CustomerController@userInfo')->middleware('wechat.platform.oauth');
        Route::get('/order/search/{type}', 'OrderController@index')->middleware('wechat.platform.oauth');
        Route::get('/order/{id}', 'OrderController@order')->middleware('wechat.platform.oauth');
        Route::get('/order/logistics/{id}', 'OrderController@logistics')->middleware('wechat.platform.oauth');
        Route::get('/order/ajax/logistics', 'OrderController@listLogisticJson')->middleware('wechat.platform.oauth');
        Route::get('/order/cancel/{id}', 'OrderController@cancelOrder')->middleware('wechat.platform.oauth');
        Route::post('/order/ajax/update', 'OrderController@update')->middleware('wechat.platform.oauth');
        Route::post('/order/update', 'OrderController@update')->middleware('wechat.platform.oauth');
        Route::post('/ajax/deleteorder/{id}', 'OrderController@ajaxDelete')->middleware('wechat.platform.oauth');
        if(Helpers::isMobile()){
            Route::get('/agreement', 'CustomerController@mobileAgreement');    
        }
    }
);

Route::group(
    ['prefix' => 'my'],
    function()
    {
        Route::get('/address', 'AddressController@index');
        Route::get('/address/{id}', 'AddressController@show');
        Route::post('/address/create', 'AddressController@store');
        Route::put('/address/{id}', 'AddressController@update');
        Route::get('/address/add', 'AddressController@add');
    }
);

Route::group(
    ['prefix' => 'blog'],
    function()
    {
        //Route::get('/personal', 'BlogController@personal');
        Route::get('/{id}', 'BlogController@article');
        Route::get('/', 'BlogController@getArticleList');
    }
);

//Route::auth();

Route::get('/home', 'HomeController@index')->middleware('web');
