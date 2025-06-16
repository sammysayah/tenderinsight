<?php

namespace App\Providers;

use Illuminate\Http\Request; // ✅ Import Request correctly
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('request', function ($app) {
            return Request::capture();
        });
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
    }
}
