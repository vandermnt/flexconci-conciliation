<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Filters\VendasErpFilter;
use App\Filters\VendasFilter;
use App\Filters\RecebimentosFilter;
use App\Filters\RecebimentosFuturosFilter;
use App\Filters\VendasErpSubFilter;
use App\Filters\VendasSubFilter;
use App\Filters\RecebimentosSubFilter;
use App\Filters\RecebimentosFuturosSubFilter;

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
        $this->app->bind(RecebimentosFilter::class, function ($app) {
            return new RecebimentosFilter;
        });
        $this->app->bind(RecebimentosFuturosFilter::class, function ($app) {
            return new RecebimentosFuturosFilter;
        });
        $this->app->bind(VendasErpSubFilter::class, function($app) {
            return new VendasErpSubFilter($app->make(VendasErpFilter::class));
        });
        $this->app->bind(VendasSubFilter::class, function($app) {
            return new VendasSubFilter($app->make(VendasFilter::class));
        });
        $this->app->bind(RecebimentosSubFilter::class, function($app) {
            return new RecebimentosSubFilter($app->make(RecebimentosFilter::class));
        });
        $this->app->bind(RecebimentosFuturosSubFilter::class, function($app) {
            return new RecebimentosFuturosSubFilter($app->make(RecebimentosFuturosFilter::class));
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
