<?php

namespace App\Modules\Api\User\Services;

use App\Modules\Api\User\Models\User;
use App\Modules\Api\User\Services\UserSearchService;
use Illuminate\Http\Request;

class UserService
{
    /**
     * index
     *
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request): array
    {
        $userSearchService = new UserSearchService();
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

    /**
     * show
     *
     * @param mixed $id
     *
     * @return array
     */
    public function show($id): array
    {
        // $user = User::findOrFail($id);
        $user = User::find($id);

        return $user;
    }

    /**
     * store
     *
     * @param mixed $data
     *
     * @return array
     */
    public function store($data): array
    {
        return User::create($data);
    }

    /**
     * update
     *
     * @param mixed $data
     * @param mixed $id
     *
     * @return array
     */
    public function update($data, $id): array
    {
        // $user = User::find($id)
        $user = User::find($id)->update($data);

        return $user;
    }

    /**
     * destroy
     *
     * @param mixed $id
     *
     * @return array
     */
    public function destroy($id): array
    {
        $user = User::find($id)->delete();

        return $user;
    }
}
