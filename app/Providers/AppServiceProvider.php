<?php

namespace App\Providers;

use App\Domain\Cost\CartCost;
use App\Domain\Cost\Contracts\CostInterface;
use App\Domain\Cost\DiscountCouponCost;
use App\Domain\Cost\DiscountProductsCost;
use App\Domain\Cost\ShippingCost;
use App\Events\OrderCompleted;
use App\Listeners\SendOrderDetails;
use App\Services\Cart\Cart;
use App\Services\Storage\Contracts\StorageInterface;
use App\Services\Storage\Session\SessionStorage;
use Illuminate\Support\Facades\Event;
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

        $this->app->bind(CostInterface::class, static function () {
            $cartCost = new CartCost(resolve(Cart::class));
            $shippingCost = new ShippingCost($cartCost);
            $discountProductCost = new DiscountProductsCost($shippingCost, resolve(Cart::class));
            return new DiscountCouponCost($discountProductCost);
        });

        Event::listen(OrderCompleted::class, SendOrderDetails::class);
    }
}
