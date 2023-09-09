<?php

namespace App\Modules\Api\User\Services;

final class UserSearchService
{
    /**
     * __invoke
     *
     * @param mixed $queryBuilder
     * @param mixed $request
     *
     * @return object
     */
    public function __invoke($queryBuilder, $request): object
    {
        if ($request->id) {
            $queryBuilder->where("id", "=", $request->id);
        }

        if ($request->uuid) {
            $queryBuilder->where("uuid", "=", $request->uuid);
        }

        if ($request->name) {
            $queryBuilder->where("name", "like", $request->name);
        }

        if ($request->email) {
            $queryBuilder->where("email", "like", $request->email);
        }

        if ($request->is_admin) {
            $queryBuilder->where("is_admin", "=", $request->is_admin);
        }

        if ($request->is_active) {
            $queryBuilder->where("is_active", "=", $request->is_active);
        }

        if ($request->created_by) {
            $queryBuilder->where("created_by", "=", $request->created_by);
        }

        if ($request->updated_by) {
            $queryBuilder->where("updated_by", "=", $request->updated_by);
        }

        if ($request->order) {
            $order = ("asc" == $request->order) ? "asc" : "desc";
            $queryBuilder->orderBy("id", $order);
        }

        return $queryBuilder->paginate(($request->count) ? $request->count : 10);
    }
}
