<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // MySQL < 5.7 requires a different default string length
        if (env('DB_MYSQL56_CUSTOM_STRING_LENGTH')) {
            Schema::defaultStringLength(191);
        }
    }
}
