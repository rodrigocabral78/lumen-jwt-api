<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

$router->get('/', function () use ($router) {
    echo gethostname() . '<br>';
    echo Carbon::now() . '<br>';

    return $router->app->version();
});

$router->get('phpinfo', function () {
    phpinfo();
});

$router->get('user', [
    'as'         => 'users',
    'middleware' => 'auth:api',
    function (Request $request) {
        return $request->user();
    },
]);

// $router->group([
//     'as'         => 'v1',
//     'middleware' => 'auth:api',
//     'prefix'     => 'v1/',
// ], function () use ($router) {
//     $router->get('users', [
//         'as'   => 'users',
//         'uses' => 'UserController@index',
//     ]);
// });

// $router->any('{any}', function () use ($router) {
//     return response()->json([
//         'status'  => 'error',
//         'message' => 'Resource not found',
//     ], Response::HTTP_NOT_FOUND);
// })->where('any', '.*');
