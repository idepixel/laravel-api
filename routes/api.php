<?php

/**
 *  @package        laravel-api.routes
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'throttle:10,1'], function() {

    // Obtiene todas las rutas registradas en el sistema
    Route::get('routes', function() {

        \Artisan::call('route:list');
        return \Artisan::output();
    });

    // Rutas generales que no requieren autenticación
    Route::get('/ping',         'API\PingController@index');
    Route::post('/users/store', 'API\Users\UserController@store');

    // Grupo de rutas de autenticación
    Route::group(['prefix' => 'auth'], function() {

        // Ruta para iniciar sesión
        Route::post('/login', 'API\Users\AuthController@login');

        // Ruta para activar el usuario
        Route::post('/activate/{token}', 'API\Users\AuthController@activate');

        // Rutas para solicitud de correo de reinicio de contraseña
        Route::group(['prefix' => 'password'], function () {

            Route::get('/find/{token}', 'API\Users\AuthController@pass_find');
            Route::post('/create',      'API\Users\AuthController@pass_create');
            Route::post('/reset',       'API\Users\AuthController@pass_reset');
        });

    });

    // Grupo de rutas que requieren Token de autenticación
    Route::group(['middleware' => 'auth:api'], function() {

        //Ruta para cerrar sesión de usuario
        Route::get('/auth/logout', 'API\Users\AuthController@logout');

        // Grupo de rutas para gestión de usuarios
        Route::group(['prefix' => 'users'], function() {

            Route::delete('/delete/{uuid}', 'API\Users\UserController@delete');

            // Grupo de rutas para gestión de perfil de usuario
            Route::group(['prefix' => 'profile'], function() {

                Route::get('/',               'API\Users\ProfileController@index');
                Route::get('/show/{uuid}',    'API\Users\ProfileController@show');
                Route::post('/update/{uuid}', 'API\Users\ProfileController@update');
                Route::post('/password',      'API\Users\ProfileController@password');
            });
        });

        // Grupo de rutas para gestión de roles y permisos
        Route::group(['prefix' => 'roles'], function() {

            Route::get('/',          'API\Users\RoleController@index');
            Route::post('/store',    'API\Users\RoleController@store');
            Route::post('/assign',   'API\Users\RoleController@assign');
            Route::post('/revoke',   'API\Users\RoleController@revoke');
            Route::delete('/delete', 'API\Users\RoleController@delete');
        });

        Route::group(['prefix' => 'permissions'], function() {

            Route::get('/',          'API\Users\PermissionController@index');
            Route::post('/store',    'API\Users\PermissionController@store');
            Route::post('/assign',   'API\Users\PermissionController@assign');
            Route::post('/revoke',   'API\Users\PermissionController@revoke');
            Route::delete('/delete', 'API\Users\PermissionController@delete');
        });
    });
});
