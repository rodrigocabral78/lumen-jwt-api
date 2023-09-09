<?php

namespace App\Modules\Api\User\Services;

use App\Modules\Api\User\Models\User;
use App\Modules\Api\User\Services\UserSearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * index.
     */
    public function index(Request $request): mixed
    {
        Log::info('Search User');

        $data = (new UserSearchService())(User::with([]), $request)->toArray();

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

    /**
     * show.
     */
    public function show($id): mixed
    {
        Log::info('Find User');

        // $user = User::findOrFail($id);
        $user = User::find($id);

        return $user;
    }

    /**
     * store.
     */
    public function store($data): mixed
    {
        Log::info('Create User');

        return User::create($data);
    }

    /**
     * update.
     */
    public function update($data, $id): mixed
    {
        Log::info('Find & Update User');

        return User::find($id)->update($data);
    }

    /**
     * destroy.
     */
    public function destroy($id): mixed
    {
        Log::info('Find & Delete User');

        return User::find($id)->delete();
    }
}
