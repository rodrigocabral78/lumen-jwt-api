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
    'as'         => 'v1.users',
    'middleware' => 'auth:api',
    'namespace'  => '\App\Modules\Api\User\Controllers',
    'prefix'     => 'v1/users',

], function () use ($router) {
    $router->get('', [
        'as'   => 'index',
        'uses' => 'UserController@index',
    ]);
    $router->post('', [
        'as'   => 'store',
        'uses' => 'UserController@store',
    ]);

    // Required Parameters
    $router->get('{id}', [
        'as'   => 'show',
        'uses' => 'UserController@show',
    ]);
    $router->put('{id}', [
        'as'   => 'update',
        'uses' => 'UserController@update',
    ]);
    $router->patch('{id}', [
        'as'   => 'update',
        'uses' => 'UserController@update',
    ]);
    $router->delete('{id}', [
        'as'   => 'destroy',
        'uses' => 'UserController@destroy',
    ]);
});
