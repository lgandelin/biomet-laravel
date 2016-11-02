<?php

Route::group(['middleware' => ['web']], function () {

    //LOGIN
    Route::get('/login', array('as' => 'login', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@login'));
    Route::post('/login', array('as' => 'login_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@authenticate'));
    Route::get('/logout', array('as' => 'logout', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@logout'));
    Route::get('/forgotten_password', array('as' => 'forgotten_password', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password'));
    Route::post('/forgotten_password_handler', array('as' => 'forgotten_password_handler', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\LoginController@forgotten_password_handler'));

    Route::get('/', array('as' => 'index', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\IndexController@index'));
});
