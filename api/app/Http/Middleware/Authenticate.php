<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): mixed
    {
        Log::info('Middleware Authenticate');

        // dd($request, $guard);
        // if ($this->auth->guard($guard)->guest()) {
        //     Log::warning('Unauthorized');

        //     return response()->json(['message' => 'Unauthorized.'], Response::HTTP_UNAUTHORIZED);
        // }

        // $bearer = $request->bearerToken();
        // dd($bearer);

        try {
            $user = JWTAuth::parseToken()->authenticate();
            // dd($user);
            if (!$user) {
                Log::warning('User not Found');

                return response()->json(['message' => 'User not Found'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            if ($e instanceof TokenBlacklistedException) {
                Log::warning('Token is Blacklisted', ['error' => $e]);

                return response()->json(['message' => 'Token is Blacklisted'], Response::HTTP_BAD_REQUEST);
            } elseif ($e instanceof TokenExpiredException) {
                Log::warning('Token is Expired', ['error' => $e]);

                return response()->json(['message' => 'Token is Expired'], Response::HTTP_UNAUTHORIZED);
            } elseif ($e instanceof TokenInvalidException) {
                Log::warning('Token is Invalid', ['error' => $e]);

                return response()->json(['message' => 'Token is Invalid'], Response::HTTP_FORBIDDEN);
            }
            Log::warning('Authorization Token not found', ['error' => $e]);

            return response()->json(['message' => 'Authorization Token not found'], Response::HTTP_NOT_FOUND);
        }

        return $next($request);
    }
}
