<?php

namespace App\Modules\Api\User\Services;

use App\Modules\Api\User\Models\User;
use App\Modules\Api\User\Services\UserSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserService
{
    public function index(Request $request)
    {
        $userSearchService = new UserSearchService();
        // return $userSearchService->search(User::with([]), $request);
        $data = $userSearchService(User::with([]), $request)->toArray();

        return [
            'total_count' => $data['total'],
            'limit'       => $data['per_page'],
            'data'        => $data['data'],
            'paginate'    => [
                'path_url'     => $data['path'],
                'first_page'   => $data['first_page_url'],
                'prev_page'    => $data['prev_page_url'],
                'current_page' => $data['current_page'],
                'next_page'    => $data['next_page_url'],
                'last_page'    => $data['last_page_url'],
                'from'         => $data['from'],
                'to'           => $data['to'],
            ],
        ];
    }

    public function show($id)
    {
        // $user = User::findOrFail($id);
        $user = User::find($id);

        return $user;
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            "uuid"       => "required",
            "name"       => "required",
            "email"      => "required",
            "password"   => "required",
            "is_admin"   => "required",
            "is_active"  => "required",
            "created_by" => "nullable",
            "updated_by" => "nullable",
        ]);

        if ($validator->errors()->count()) {
            throw new \Exception($validator->errors()->first(), 400);
        }

        if (!$validator->errors()->count()) {
            $data = [
                "uuid"       => $request->uuid,
                "name"       => $request->name,
                "email"      => $request->email,
                "password"   => $request->password,
                "is_admin"   => $request->is_admin,
                "is_active"  => $request->is_active,
                "created_by" => $request->created_by,
                "updated_by" => $request->updated_by,
            ];

            return User::create($data);
        }
    }

    public function update($request, $id)
    {
        // $user = User::find($id);

        $validator = Validator::make($request->all(), [
            "uuid"       => "required",
            "name"       => "required",
            "email"      => "required",
            "password"   => "required",
            "is_admin"   => "required",
            "is_active"  => "required",
            "created_by" => "nullable",
            "updated_by" => "nullable",
        ]);

        if ($validator->errors()->count()) {
            throw new \Exception($validator->errors()->first(), 400);
        }

        if (!$validator->errors()->count()) {
            $data = [
                "uuid"       => $request->uuid,
                "name"       => $request->name,
                "email"      => $request->email,
                "password"   => $request->password,
                "is_admin"   => $request->is_admin,
                "is_active"  => $request->is_active,
                "created_by" => $request->created_by,
                "updated_by" => $request->updated_by,
            ];
            $user = User::find($id)->update($data);
        }

        return $user;
    }

    public function destroy($id)
    {
        $user = User::find($id)->delete();

        return $user;
    }
}
