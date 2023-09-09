<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Api\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRegistrationController extends Controller
{
    public function __invoke(Request $request): mixed
    {
        // $this->middleware('auth:api', ['except' => ['store']]);
        try {
            Log::info('Store User Registration');

            $this->validate($request, [
                'name'                  => 'required|string|min:6|max:254',
                'email'                 => 'sometimes|required|string|max:254|email|unique:users',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6|same:password',
            ]);

            $user = (new UserService)->store($request->only([
                'name', 'email', 'password'
            ]));

            $token = Auth::login($user);

            return response()->json([
                'access_token' => $token,
                'token_type'   => 'bearer',
                'expires_in'   => Auth::factory()->getTTL() * 60,
                'user'         => Auth::user(),
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return response()->json([
                // 'error'  => false,
                'message' => $th->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
