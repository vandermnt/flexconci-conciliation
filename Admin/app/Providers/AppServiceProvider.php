<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;
use App\Filters\VendasErpSubFilter;
use App\Filters\VendasSubFilter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(VendasErpFilter::class, function ($app) {
            return new VendasErpFilter;
        });
        $this->app->bind(VendasFilter::class, function ($app) {
            return new VendasFilter;
        });
        $this->app->bind(VendasErpSubFilter::class, function($app) {
            return new VendasErpSubFilter($app->make(VendasErpFilter::class));
        });
        $this->app->bind(VendasSubFilter::class, function($app) {
            return new VendasSubFilter($app->make(VendasFilter::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
