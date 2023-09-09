<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * index.
     */
    public function index(): mixed
    {
    }

    /**
     * store.
     */
    public function store(Request $request): mixed
    {
        Log::info('Store Authentication');

        $this->validate($request, [
            'email'    => 'sometimes|required|string|email|max:254',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (!Auth::validate($credentials)) {
            Log::warning('Credentials are not valid');

            return response()->json([
                'message' => 'Credentials are not valid',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = Auth::attempt($credentials);
        if (!$token) {
            Log::warning('Unauthorized authentication');

            return response()->json([
                'message' => 'Unauthorized authentication',
            ], Response::HTTP_FORBIDDEN);
        }

        return $this->replyWithToken($token);
    }

    /**
     * show.
     */
    public function show(): mixed
    {
        Log::info('Show User Authentication');

        return response()->json(Auth::user());
    }

    /**
     * update.
     */
    public function update(): mixed
    {
        Log::info('Update Token Authentication');

        return $this->replyWithToken(Auth::refresh(true, true));
    }

    /**
     * destroy.
     */
    public function destroy(): mixed
    {
        Log::info('Destroy User Authentication');

        Auth::logout();
        Auth::invalidate(true);

        return response()->json([
            'message' => 'Logout with success!',
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * replyWithToken.
     */
    protected function replyWithToken(string $token): mixed
    {
        Log::info('Reply with Token Authentication');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::factory()->getTTL() * 60,
            'date_time'    => Carbon::now()->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }
}
