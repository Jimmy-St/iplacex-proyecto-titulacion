<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // JSON UNICODE
        Response::macro('jsonUnicode', function ($data, $status = 200, $headers = []) {
            return response()->json($data, $status, $headers, JSON_UNESCAPED_UNICODE);
        });
    }
}
