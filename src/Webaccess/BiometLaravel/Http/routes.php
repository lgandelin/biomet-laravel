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

        //HEURES EN FONCTIONNEMENT
        Route::get('/site/equipements/{facility_id}/{id}/reset', array('as' => 'equipments_reset_functionning_hours', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\EquipmentController@reset_functionning_hours'));

        //FACILITY
        Route::get('/site/{id}', array('as' => 'facility', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@index'));

        //INTERVENTIONS
        Route::get('/site/{id}/10/ajouter_intervention', array('as' => 'interventions_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@add'));
        Route::post('/site/interventions/ajouter', array('as' => 'interventions_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@store'));
        Route::get('/site/interventions/{id}', array('as' => 'interventions_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@edit'));
        Route::post('/site/interventions', array('as' => 'interventions_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@update'));
        Route::get('/site/interventions/supprimer/{id}', array('as' => 'interventions_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@delete'));

        Route::get('/site/interventions/{id}/fichiers/telecharger/{file_name}', array('as' => 'interventions_download_file', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@download_file'));
        Route::get('/site/interventions/{id}/fichiers/supprimer/{file_name}', array('as' => 'interventions_delete_file', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\InterventionController@delete_file'));

        Route::get('/site/{id}/download_file/{year?}/{month?}/{day?}', array('as' => 'facility_download_file', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@download_file'));
        Route::get('/site/{id}/{tab}/{year?}/{month?}/{day?}', array('as' => 'facility_tab', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@tab'));
        Route::post('/site/graph', array('as' => 'facility_get_graph', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@graph'));
        Route::post('/site/excel', array('as' => 'facility_get_excel', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@excel'));
        Route::post('/site/group_excel', array('as' => 'facility_group_excel', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\FacilityController@group_excel'));

        //ADMIN AROL ENERGY
        Route::group(['middleware' => ['admin']], function () {

            Route::get('/admin/utilisateurs', array('as' => 'users', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@index'));
            Route::get('/admin/utilisateurs/ajouter', array('as' => 'users_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@add'));
            Route::post('/admin/utilisateurs/ajouter', array('as' => 'users_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@store'));
            Route::get('/admin/utilisateurs/{id}', array('as' => 'users_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@edit'));
            Route::post('/admin/utilisateurs', array('as' => 'users_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@update'));
            Route::get('/admin/utilisateurs/supprimer/{id}', array('as' => 'users_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\UserController@delete'));

            Route::get('/admin/clients', array('as' => 'clients', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@index'));
            Route::get('/admin/clients/ajouter', array('as' => 'clients_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@add'));
            Route::post('/admin/clients/ajouter', array('as' => 'clients_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@store'));
            Route::get('/admin/clients/{id}', array('as' => 'clients_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@edit'));
            Route::post('/admin/clients', array('as' => 'clients_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@update'));
            Route::get('/admin/clients/supprimer/{id}', array('as' => 'clients_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientController@delete'));

            Route::get('/admin/sites', array('as' => 'facilities', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@index'));
            Route::get('/admin/sites/ajouter', array('as' => 'facilities_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@add'));
            Route::post('/admin/sites/ajouter', array('as' => 'facilities_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@store'));
            Route::get('/admin/sites/{id}', array('as' => 'facilities_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@edit'));
            Route::post('/admin/sites', array('as' => 'facilities_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@update'));
            Route::get('/admin/sites/supprimer/{id}', array('as' => 'facilities_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\FacilityController@delete'));
        });

        //ADMIN CLIENT
        Route::group(['middleware' => ['admin_clients']], function() {

            Route::get('/admin/comptes', array('as' => 'client_users', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@index'));
            Route::get('/admin/comptes/ajouter', array('as' => 'client_users_add', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@add'));
            Route::post('/admin/comptes/ajouter', array('as' => 'client_users_store', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@store'));
            Route::get('/admin/comptes/{id}', array('as' => 'client_users_edit', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@edit'));
            Route::post('/admin/comptes', array('as' => 'client_users_update', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@update'));
            Route::get('/admin/comptes/supprimer/{id}', array('as' => 'client_users_delete', 'uses' => 'Webaccess\BiometLaravel\Http\Controllers\Admin\ClientUserController@delete'));
        });
    });
});
