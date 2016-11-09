<?php

//WEB
Route::group(['middleware' => ['web']], function () {

    Route::get('/login', array('as' => 'login', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password_handler'));

    //AUTH
    Route::group(['middleware' => ['auth']], function () {

        Route::get('/', array('as' => 'dashboard', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\DashboardController@index'));
        Route::get('/site/{id}', array('as' => 'facility', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@view'));

        //ADMIN
        Route::group(['middleware' => ['admin']], function () {

            Route::get('/admin/utilisateurs', array('as' => 'users', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@index'));
            Route::get('/admin/utilisateurs/ajouter', array('as' => 'users_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@add'));
            Route::post('/admin/utilisateurs/ajouter', array('as' => 'users_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@store'));
            Route::get('/admin/utilisateurs/{id}', array('as' => 'users_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@edit'));
            Route::post('/admin/utilisateurs', array('as' => 'users_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@update'));
            Route::get('/admin/utilisateurs/supprimer/{id}', array('as' => 'users_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@delete'));

            Route::get('/admin/clients', array('as' => 'clients', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@index'));
            Route::get('/admin/clients/ajouter', array('as' => 'clients_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@add'));
            Route::post('/admin/clients/ajouter', array('as' => 'clients_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@store'));
            Route::get('/admin/clients/{id}', array('as' => 'clients_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@edit'));
            Route::post('/admin/clients', array('as' => 'clients_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@update'));
            Route::get('/admin/clients/supprimer/{id}', array('as' => 'clients_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@delete'));

            Route::get('/admin/sites', array('as' => 'facilities', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@index'));
            Route::get('/admin/sites/ajouter', array('as' => 'facilities_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@add'));
            Route::post('/admin/sites/ajouter', array('as' => 'facilities_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@store'));
            Route::get('/admin/sites/{id}', array('as' => 'facilities_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@edit'));
            Route::post('/admin/sites', array('as' => 'facilities_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@update'));
            Route::get('/admin/sites/supprimer/{id}', array('as' => 'facilities_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@delete'));
        });
    });
});
