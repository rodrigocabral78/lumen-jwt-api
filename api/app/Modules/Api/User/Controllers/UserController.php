<?php

namespace App\Modules\Api\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Api\User\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * index
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request): mixed
    {
        Log::info('Index User');

        $data = $this->userService->index($request);

        return response()->json($data, 200);
    }

    /**
     * show
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id): mixed
    {
        Log::info('Show User');

        $data = $this->userService->show($id);

        return response()->json($data, 200);
    }

    /**
     * store
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request): mixed
    {
        Log::info('Store User');

        $validator = $this->validate($request, [
            'name'                  => 'required|string|min:6|max:254',
            'email'                 => 'sometimes|required|string|email|max:254|unique:users',
            'password'              => 'required|string|min:6|confirmed',
            'is_admin'              => 'required|boolean',
            'is_active'             => 'required|boolean',
        ]);

        dd($validator);
        // Illuminate\Validation\ValidationException
        // if ($validator->errors()->count()) {
        //     throw new \Exception($validator->errors()->first(), 400);
        // }

        $data = $this->userService->store(
            $request->only([
                'name', 'email', 'password', 'is_admin', 'is_active'
            ])
        );

        return response()->json($data, 201);
    }

    /**
     * update
     *
     * @param Request $request
     * @param mixed $id
     *
     * @return mixed
     */
    public function update(Request $request, $id): mixed
    {
        Log::info('Update User');

        $validator = $this->validate($request, [
            'name'                  => 'required|string|min:6|max:254',
            'email'                 => 'sometimes|required|string|email|max:254|unique:users',
            'password'              => 'required|string|min:6|confirmed',
            'is_admin'              => 'required|boolean',
            'is_active'             => 'required|boolean',
        ]);

        dd($validator);

        $data = $this->userService->update(
            $request->only([
                'name', 'email', 'password', 'is_admin', 'is_active'
            ]),
            $id
        );

        return response()->json($data, 200);
    }

    /**
     * destroy
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function destroy($id): mixed
    {
        Log::info('Destroy User');

        $data = $this->userService->destroy($id);

        // return response()->json($data, 200);
        return response()->json($data, 204);
    }
}
