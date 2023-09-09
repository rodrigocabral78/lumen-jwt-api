<?php

namespace App\Modules\Api\User\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class UserSearchService
{
    /**
     * __invoke.
     */
    public function __invoke($queryBuilder, $request): object
    {
        Log::info('Invoke');

        if ($request->id) {
            Log::info('Id');

            $queryBuilder->where("id", "=", $request->id);
        }

        if ($request->uuid) {
            Log::info('Uuid');

            $queryBuilder->where("uuid", "=", $request->uuid);
        }

        if ($request->name) {
            Log::info('Name');

            $queryBuilder->where("name", "like", "%" . Str::of($request->name)->trim() . "%");
        }

        if ($request->email) {
            Log::info('Email');

            $queryBuilder->where("email", "like", "%" . Str::of($request->email)->trim() . "%");
        }

        if ($request->is_admin) {
            Log::info('Is admin');

            $queryBuilder->where("is_admin", "=", $request->is_admin);
        }

        if ($request->is_active) {
            Log::info('Is active');

            $queryBuilder->where("is_active", "=", $request->is_active);
        }

        if ($request->created_by) {
            Log::info('Created by');

            $queryBuilder->where("created_by", "=", $request->created_by);
        }

        if ($request->updated_by) {
            Log::info('Updated by');

            $queryBuilder->where("updated_by", "=", $request->updated_by);
        }

        if ($request->order) {
            Log::info('Order By Id');

            $order = ("asc" == $request->order) ? "asc" : "desc";
            $queryBuilder->orderBy("id", $order);
        }

        return $queryBuilder->paginate(($request->count) ? $request->count : 10);
    }
}
