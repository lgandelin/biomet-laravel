<?php

Route::group(['middleware' => ['web']], function () {

    //LOGIN
    Route::get('/login', array('as' => 'login', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password_handler'));

    Route::get('/', array('as' => 'dashboard', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\DashboardController@index'));

    //USERS
    Route::get('/users', array('as' => 'users', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@index'));
    Route::get('/users/add', array('as' => 'users_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@add'));
    Route::post('/users/add', array('as' => 'users_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@store'));
    Route::get('/users/{id}', array('as' => 'users_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@edit'));
    Route::post('/users', array('as' => 'users_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@update'));
    Route::get('/users/delete/{id}', array('as' => 'users_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\UserController@delete'));
    
    //CLIENTS
    Route::get('/clients', array('as' => 'clients', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@index'));
    Route::get('/clients/add', array('as' => 'clients_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@add'));
    Route::post('/clients/add', array('as' => 'clients_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@store'));
    Route::get('/clients/{id}', array('as' => 'clients_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@edit'));
    Route::post('/clients', array('as' => 'clients_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@update'));
    Route::get('/clients/delete/{id}', array('as' => 'clients_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\ClientController@delete'));
});
