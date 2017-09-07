<?php

Route::group(
    ['prefix' => Config('constants.TEACHER_PREFIX', "teach"),
     'middleware' => ['teach.auth:teach'],
     'namespace' => 'Teach'
    ], function () 
    {
        Route::auth();
    });
Route::group(
    ['prefix' => Config('constants.TEACHER_PREFIX', "teach").'/app',
     'middleware' => ['teach.auth:teach'],
     'namespace' => 'Teach'
    ], function () 
    {
        Route::get('/', 'AppController@app');
    });

Route::group(
    ['prefix' => Config('constants.TEACHER_PREFIX', "teach").'/rest',
     'middleware' => ['teach.auth:teach'],
     'namespace' => 'Teach'
    ], function () 
    {
        Route::get('/logininfo', 'Auth\AuthController@logininfo');
        Route::resource('/student', 'StudentController');
        Route::get('/student/{id}/advise', 'StudentController@advise');
        Route::put('/student/{id}/advise', 'StudentController@update_advise');
        Route::get('/student/{id}/fgrade', 'StudentController@fgrade');
        Route::put('/student/{id}/fgrade', 'StudentController@update_fgrade');
        Route::resource('/class', 'ClassController');
    });