<?php

Route::group(
    ['prefix' => Config('constants.ADMIN_PREFIX'),
     'middleware' => ['admin.auth:admin'],
     'namespace' => 'Admin'
    ], function () 
{
        Route::auth();

        Route::get('/visit/visitor/template', 'Visit\VisitorController@template');
        Route::get('/visit/visitor/down', 'Visit\VisitorController@down');
        Route::put('/visit/visitor/vaildvisitorcodeunique', 'Visit\VisitorController@vaildVisitorCodeUnique');
        Route::post('/visit/visitor/createsinglecode', 'Visit\VisitorController@createSingleCode');
        Route::get('/visit/visitor/uploadfilepage', 'Visit\VisitorController@uploadFilePage');
        Route::post('/visit/visitor/uploadfile', 'Visit\VisitorController@uploadFile');
        Route::post('/visit/visitor/createcode', 'Visit\VisitorController@createCode');
        Route::post('/visit/visitor/sendcode', 'Visit\VisitorController@sendCode');
        Route::get('/visit/visitor/list', 'Visit\VisitorController@listJson');
        Route::resource('/visit/visitor', 'Visit\VisitorController');
        // shop

        Route::get('/friendlink/list','FriendLinkController@listJson');
        Route::resource('/friendlink', 'FriendLinkController');


        Route::get('/class/list','ClassController@listJson');
        Route::resource('/class', 'ClassController');
    Route::get('/class/delete/{id}','ClassController@delete');


    Route::get('/school/list','SchoolController@listJson');
    Route::any('/school/phpexcel','SchoolController@phpexcel');
    Route::resource('/school', 'SchoolController');
    Route::get('/school/delete/{id}','SchoolController@delete');
    Route::any('upload','CommonController@upload');
    Route::any('/upimg','CommonController@upimg');


    Route::get('/teacher/list','TeacherController@listJson');
    Route::any('/teacher/phpexcel','TeacherController@phpexcel');
    Route::resource('/teacher', 'TeacherController');
    Route::get('/teacher/delete/{id}','TeacherController@delete');
    Route::any('/upimgteacher','CommonController@upimgteacher');


    Route::get('/customer/list','CustomerController@listJson');
    Route::any('/customer/phpexcel','CustomerController@phpexcel');
    Route::resource('/customer', 'CustomerController');
    Route::get('/customer/delete/{id}','CustomerController@delete');
    Route::any('/upimgcustomer','CommonController@upimgcustomer');
        Route::get('/shop/order/ajaxList','Shop\OrderController@listJson');
        Route::get('/shop/order/logisticOnline','Shop\OrderController@listLogisticJson');


        Route::resource('/shop/order', 'Shop\OrderController');
        Route::resource('/shop/product', 'Shop\ProductController');

        Route::get('/shop/product/copy/{id}', 'Shop\ProductController@copyProduct');
        Route::get('/shop/category/delcategory', 'Shop\CategoryController@delCategory');
        Route::get('/shop/category/createcategory', 'Shop\CategoryController@createCategory');
        Route::get('/shop/category/getchildcategory', 'Shop\CategoryController@getChildCategory');
        Route::get('/shop/category/list', 'Shop\CategoryController@listJson');
        Route::resource('/shop/category', 'Shop\CategoryController');


        Route::get('/shop/attributevalue/delattributevalue', 'Shop\AttributeValueController@delAttributeValue');
        Route::get('/shop/attributevalue/getattributevalue', 'Shop\AttributeValueController@getAttributeValue');
        Route::get('/shop/attributevalue/editattributevalue', 'Shop\AttributeValueController@editAttributeValue');
        Route::get('/shop/attributevalue/listbyid', 'Shop\AttributeValueController@listJsonById');
        Route::get('/shop/attributevalue/addvalue', 'Shop\AttributeValueController@addValue');
        Route::get('/shop/attributevalue/list', 'Shop\AttributeValueController@listJson');
        Route::resource('/shop/attributevalue', 'Shop\AttributeValueController');

        Route::get('/shop/attribute/attributeinfo', 'Shop\AttributeController@attributeInfo');
        Route::get('/shop/attribute/delattribute', 'Shop\AttributeController@delAttribute');
        Route::get('/shop/attribute/addchildattribute', 'Shop\AttributeController@addChildAttribute');
        Route::get('/shop/attribute/createattribute', 'Shop\AttributeController@createAttribute');
        Route::get('/shop/attribute/addattribute', 'Shop\AttributeController@addAttribute');
        Route::get('/shop/attribute/getattribute', 'Shop\AttributeController@getAttribute');
        Route::get('/shop/attribute/editattribute', 'Shop\AttributeController@editAttribute');
        Route::get('/shop/attribute/list', 'Shop\AttributeController@listJson');
        Route::resource('/shop/attribute', 'Shop\AttributeController');

        Route::get('/shop/brand/list', 'Shop\BrandController@listJson');
        Route::resource('/shop/brand', 'Shop\BrandController');
        Route::resource('/shop/coupon', 'Shop\CouponController');

        Route::get('/product/lists', 'Shop\ProductController@listJson');
        Route::get('/product/getcategoryattributes/{id}','Shop\ProductController@ajaxGetCategoryAttributes');

        // content
        Route::get('/content/getarticleslistbyname','ContentController@getArticlesListByName');
        Route::get('/content/allcontents', 'ContentController@getAllContents');
        Route::get('/content/lists', 'ContentController@listJson');
        Route::get('/contentcatalog/cutimage','ContentCatalogController@cuttingImage');
        Route::get('/contentcatalog/childrenlists','ContentCatalogController@childrenListJson');
        Route::get('/contentcatalog/getchildrencategory','ContentCatalogController@getChildernCategorys');
        Route::get('/contentcatalog/getparentcategory','ContentCatalogController@getParentCategorys');
        Route::resource('/content', 'ContentController');
        Route::resource('/contentcatalog', 'ContentCatalogController');

        // content tag
        Route::post('/contenttag/tagListsHtml', 'ContentTagController@listTagsHtml');
        Route::get('/contenttag/tagLists', 'ContentTagController@listJson');
        Route::get('/contenttag/catLists', 'ContentTagController@categoryListJson');
        Route::get('/contenttag/category/{id}/edit', 'ContentTagController@categoryIndex');
        Route::put('/contenttag/category/{id}', 'ContentTagController@updateCategory');
        Route::post('/contenttag/generateTags', 'ContentTagController@ajaxReGenerateTags');
        Route::post('/contenttag/newTag', 'ContentTagController@ajaxNewTag');
        Route::post('/contenttag/modTag', 'ContentTagController@ajaxUpdateTag');
        Route::delete('/contenttag/delTag', 'ContentTagController@ajaxDeleteTag');

        Route::resource('/contenttag', 'ContentTagController');
        Route::resource('/dashboard', 'DashboardController');

        // entrust
        Route::get('/role/lists', 'RoleController@listJson');
        Route::get('/role/refreshRolesPerms','RoleController@refreshRolesPerms');
        Route::resource('/role', 'RoleController');
        Route::get('/userrole/lists', 'UserRoleController@listJson');
        Route::resource('/userrole', 'UserRoleController');

        // ad sys
        Route::post('/adposition/kvorder','AdPositionController@HomeKVOrder');
        Route::post('/adposition/editbase','AdPositionController@editBase');
        Route::post('/adposition/savenode','AdPositionController@saveNode');
        Route::post('/adposition/reset','AdPositionController@resetNode');
        Route::get('/adposition/lists', 'AdPositionController@listJson');
        Route::get('/adposition/createadpositionforcategory', 'AdPositionController@fillAdpositionForCategory');
        Route::resource('/adposition', 'AdPositionController');
        
        
        //nodeAttrType
        Route::get('/nodeattrtype/list','NodeAttrTypeController@listJson');
        Route::resource('/nodeattrtype', 'NodeAttrTypeController');
        
        //nodeType
        Route::post('/nodetype/setnodetypeattr','NodeTypeController@setNodeTypeAttr');
        Route::get('/nodetype/list','NodeTypeController@listJson');
        Route::resource('/nodetype', 'NodeTypeController');

        // node
        Route::get('/node/lists', 'NodeController@listJson');
        Route::resource('/node', 'NodeController');
        // node attribute
        Route::post('/nodeattr/saveattr', 'NodeAttrController@saveAttrJson');
        Route::get('/nodeattr/lists', 'NodeAttrController@listJson');
        Route::resource('/nodeattr', 'NodeAttrController');

        //setting-sys
        Route::post('/generalsetting/save', 'GeneralSettingController@save');
        Route::resource('/generalsetting', 'GeneralSettingController');

        //other content
        Route::get('/othercontent/lists', 'OtherContentController@listJson');
        Route::resource('/othercontent','OtherContentController');
        Route::get('/', function(){ return redirect(action('Admin\DashboardController@index')); });
});
