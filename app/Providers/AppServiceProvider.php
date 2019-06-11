<?php

namespace App\Providers;

use App\Discount;
use App\Shop;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        view()->share('header_shops', count(Shop::get()));
        view()->share('header_discounts', count(Discount::get()));
    }
}
