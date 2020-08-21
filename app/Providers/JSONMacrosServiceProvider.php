<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class JSONMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register the application's response macros.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('json_fail', function ($err, $arr = []) {
            return response()->json(
                array_merge(['success' => false, 'error' => $err], $arr),
                200, [], JSON_UNESCAPED_UNICODE
            );
        });

        Response::macro('json_success', function ($arr = []) {
            return response()->json(
                array_merge(['success' => true], $arr),
                200, [], JSON_UNESCAPED_UNICODE
            );
        });
    }
}
