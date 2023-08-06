<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });

        /*
         * https://cursos.alura.com.br/forum/topico-jwt-decode-198365
         */
        // $this->app['auth']->viaRequest('api', function ($request) {
        //     dd($request);
        //     die();
        //     if (!$request->hasHeader('Authorization')) {
        //         return null;
        //     }
        //     $authorizationHeader = $request->header('Authorization');
        //     $token               = str_replace('Bearer ', '', $authorizationHeader);

        //     $dadosAuth = JWT::decode($token, env('JWT_KEY'), ['HS256']);

        //     return User::where('email', $dadosAuth->email)->first();
        // });

        // $this->app['auth']->viaRequest('api', function ($request) {
        //     dd($request);
        //     die();
        //     if (!$request->hasHeader('Authorization')) {
        //         return null;
        //     }
        //     $authorizationHeader = $request->header('Authorization');
        //     $token               = str_replace('Bearer ', '', $authorizationHeader);

        //     $key = new Key(env('JWT_KEY'), 'HS256'); // ---> passei a key desta forma

        //     $dadosAuth = JWT::decode($token, $key);

        //     return User::where('email', $dadosAuth->email)->first();
        // });
    }
}
