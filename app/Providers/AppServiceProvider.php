<?php

namespace App\Providers;

use App\Services\Storage\Contracts\StorageInterface;
use App\Services\Storage\Session\SessionStorage;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(StorageInterface::class, static function () {
            return new SessionStorage('cart');
        });
    }
}
