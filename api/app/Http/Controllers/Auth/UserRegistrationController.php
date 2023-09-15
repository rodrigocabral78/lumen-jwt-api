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
    /**
     * Register.
     *
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     type="object",
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="email",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="password",
     *                         type="string"
     *                     )
     *                 ),
     *                 example={
     *                     "name" :"John",
     *                     "email" :"john@test.com",
     *                     "password" :"johnjohn1"
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="code", type="number", example=200),
     *                 @OA\Property(property="status", type="string", example="success"),
     *                 @OA\Property(property="message", type="string", example=null),
     *             ),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="number", example=1),
     *                     @OA\Property(property="name", type="string", example="John"),
     *                     @OA\Property(property="email", type="string", example="john@test.com"),
     *                     @OA\Property(property="email_verified_at", type="string", example=null),
     *                     @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
     *                     @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
     *                 ),
     *                 @OA\Property(property="access_token", type="object",
     *                     @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                     @OA\Property(property="type", type="string", example="Bearer"),
     *                     @OA\Property(property="expires_in", type="number", example=3600),
     *                 ),
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="code", type="number", example=422),
     *                 @OA\Property(property="status", type="string", example="error"),
     *                 @OA\Property(property="message", type="object",
     *                     @OA\Property(property="email", type="array", collectionFormat="multi",
     *
     *                         @OA\Items(
     *                             type="string",
     *                             example="The email has already been taken.",
     *                         )
     *                     ),
     *                 ),
     *             ),
     *
     *             @OA\Property(property="data", type="object", example={}),
     *         )
     *     )
     * )
     */
    public function __invoke(Request $request): mixed
    {
        // $this->middleware('auth:api', ['except' => ['store']]);
        try {
            Log::info('Store User Registration');

            // validate the incoming request
            // set every field as required
            // set email field so it only accept the valid email format
            $this->validate($request, [
                'name'                  => 'required|string|min:2|max:254',
                'email'                 => 'sometimes|required|string|max:254|email:rfc,dns|unique:users',
                'password'              => 'required|string|min:6|max:254|confirmed',
                'password_confirmation' => 'required|string|min:6|max:254|same:password',
            ]);

            // if the request valid, create user
            $user = (new UserService())->store($request->only([
                'name', 'email', 'password',
            ]));

            // login the user immediately and generate the token
            $token = Auth::login($user);

            // return the response as json
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
