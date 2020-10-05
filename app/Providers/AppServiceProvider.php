<?php

namespace App\Providers;

use App\Traits\BillPayment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use BillPayment;
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
        //
        getDataInfo();
        getElectricityInfo();

        Schema::defaultStringLength(191);

    }
}