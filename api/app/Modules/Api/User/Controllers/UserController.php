<?php

namespace App\Modules\Api\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Api\User\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data =  $this->userService->index($request);

        return response()->json($data, 200);
    }

    public function show($id)
    {
        $data = $this->userService->show($id);

        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $data = $this->userService->store($request);

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $this->userService->update($request, $id);

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $data = $this->userService->destroy($id);

        // return response()->json($data, 200);
        return response()->json($data, 204);
    }
}
