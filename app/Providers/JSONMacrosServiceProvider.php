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
        Response::macro('json_fail', function ($err) {
            return response()->json(
                ['success' => false, 'error' => $err],
                200, [], JSON_UNESCAPED_UNICODE
            );
        });

        Response::macro('json_success', function ($arr) {
            if (!$arr) {
                $arr = [];
            }

            return response()->json(
                array_merge(['success' => true], $arr),
                200, [], JSON_UNESCAPED_UNICODE
            );
        });
    }
}
