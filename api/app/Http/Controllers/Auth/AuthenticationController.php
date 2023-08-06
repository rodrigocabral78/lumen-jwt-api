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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Store');

        $this->validate($request, [
            'email'    => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        // $credentials = request(['email', 'password']);

        $token = Auth::attempt($credentials);
        if (!$token) {
            Log::warning("Unauthorized");

            return response()->json([
                'message' => 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->replyWithToken($token);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Log::info('Show User');

        // return response()->json($user);
        return response()->json(Auth::user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        Log::info('Update Token');

        $token = Auth::refresh();

        return $this->replyWithToken($token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout with success!',
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Undocumented function.
     *
     * @param [type] $token
     *
     * @return void
     */
    protected function replyWithToken($token)
    {
        Log::info('Reply with Token');

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            // default 1 hour
            'expires_in' => Auth::factory()->getTTL() * 60,
            'date_time'  => Carbon::now()->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }
}
