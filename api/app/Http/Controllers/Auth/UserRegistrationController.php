<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserRegistrationController extends Controller
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
        try {
            Log::info('Store');

            $this->validate($request, [
                'name'                  => 'required|string|min:6|max:255',
                'email'                 => 'required|string|email|max:255|unique:users',
                'password'              => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6|same:password',
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
                // 'created_by' => 0,
                // 'updated_by' => 0,
            ]);

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

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    }
}
