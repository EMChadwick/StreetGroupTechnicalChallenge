<?php

namespace App\Providers;

use App\Enums\Title;
use App\Services\HomeownerParser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HomeownerParser::class, function () {
            return new HomeownerParser(Title::values());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
