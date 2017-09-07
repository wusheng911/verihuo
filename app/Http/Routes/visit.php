<?php

Route::group(
    ['prefix' => Config('constants.VISIT_PREFIX'),
     'namespace' => 'Visit'
    ], function () 
{

        Route::get('/invitation', 'InvitationController@index');
        Route::post('/invitation', 'InvitationController@check_code');

        Route::get('/visitor/{code}', 'InvitationController@visitor');

        Route::get('/scan/entry/{code}', 'InvitationController@entry_visitor');

});