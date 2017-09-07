<?php
/*----------------------------------------------------------------------------
 * Below are for Api Restful request
 * -----------------------------------------------------------------------------
 * 
 */
Route::group([
    'prefix' => 'api', 
    'middleware' => ['auth.ip'],
    'namespace' => 'Api'
    ], 
    function() {
        Route::get('/articles/related/{id}', 'ArticleController@related');  
        Route::get('/artcomments/loadbyarticle/{id}', 'ArtCommentController@loadByArticle');
        Route::get('/customers/registeredby/{value}', 'CustomerController@registeredBy');
        Route::post('/customers/sendsms', 'CustomerController@sendSMS');  
        Route::get('/addresses/getzones', 'AddressController@getZones');
        Route::resource('nodes', 'NodeController');
        Route::resource('adpositions', 'AdPositionController');
        Route::resource('categories', 'CategoryController');
        Route::resource('articles', 'ArticleController');
        Route::resource('artcomments', 'ArtCommentController');
        Route::resource('customers', 'CustomerController');
        Route::resource('products', 'ProductController');
        Route::resource('skus', 'SkuController');
        Route::resource('carts', 'CartController');
        Route::resource('orders', 'OrderController');  
        Route::resource('shopcategories', 'ShopCategoryController');
        Route::resource('addresses', 'AddressController');
        Route::resource('merchants', 'MerchantController');
        Route::resource('invoices', 'InvoiceController');        
    }
);