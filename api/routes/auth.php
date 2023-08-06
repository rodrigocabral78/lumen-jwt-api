<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group([
    'as'        => 'v1.auth',
    'namespace' => 'Auth',
    'prefix'    => 'v1/auth/',
], function () use ($router) {
    $router->group([
    ], function () use ($router) {
        $router->post('login', [
            'as'   => 'login',
            'uses' => 'AuthenticationController@store',
        ]);
        $router->post('in', [
            'as'   => 'in',
            'uses' => 'AuthenticationController@store',
        ]);

        $router->post('register', [
            'as'   => 'register',
            'uses' => 'UserRegistrationController@store',
        ]);
    });

    $router->group([
        'middleware' => 'auth:api',
    ], function () use ($router) {
        $router->post('logout', [
            'as'   => 'logout',
            'uses' => 'AuthenticationController@destroy',
        ]);
        $router->post('out', [
            'as'   => 'out',
            'uses' => 'AuthenticationController@destroy',
        ]);

        $router->post('refresh', [
            'as'   => 'refresh',
            'uses' => 'AuthenticationController@update',
        ]);

        $router->post('me', [
            'as'   => 'me',
            'uses' => 'AuthenticationController@show',
        ]);
    });
});
