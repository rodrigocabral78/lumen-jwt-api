<?php

namespace App\Modules\Api\User\Services;

final class UserSearchService
{
    // public function search($queryBuilder, $request)
    public function __invoke($queryBuilder, $request)
    {
        if ($request->id) {
            $queryBuilder->where("id", "=", $request->id);
        }

        if ($request->uuid) {
            $queryBuilder->where("uuid", "=", $request->uuid);
        }

        if ($request->name) {
            $queryBuilder->where("name", "=", $request->name);
        }

        if ($request->email) {
            $queryBuilder->where("email", "=", $request->email);
        }

        if ($request->password) {
            $queryBuilder->where("password", "=", $request->password);
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
