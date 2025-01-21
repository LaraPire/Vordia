<?php

namespace Rayiumir\Vordia\ServiceProvider;

use Illuminate\Support\ServiceProvider;
use Rayiumir\Vordia\Vordia;

class VordiaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('Vordia', function() {
            return new Vordia();
        });
    }
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->_loadRoutes();
        $this->_loadViews();
        $this->_loadMigrations();
    }

    /**
     * For Load Routes
     *
     * @return void
     */
    private function _loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/vordia.php');
    }

    /**
     * For Load Views
     *
     * @return void
     */
    private function _loadViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'Vordia');
    }

    private function _loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');
    }

}

