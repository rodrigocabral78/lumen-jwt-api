<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Log all DB SELECT statements
        // @codeCoverageIgnoreStart
        if (!app()->environment('testing') && config('database.sql_logging')) {
            DB::listen(function ($query) {
                // if (preg_match('/^select/', $query->sql)) {
                //     Log::info('sql: ' . $query->sql);
                //     // Also available are $query->bindings and $query->time.
                // }
                Log::info(
                    'sql: ' . $query->sql,
                    [
                        'time'       => $query->time,
                        'bindings'   => $query->bindings,
                        'connection' => $query->connection->getConfig(),
                    ]
                );
            });
        }
    }
}
