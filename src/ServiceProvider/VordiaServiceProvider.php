<?php

namespace Rayiumir\Vordia\ServiceProvider;

use Illuminate\Support\ServiceProvider;

class VordiaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/vordia.php', 'vordia'
        );
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
        $this->_loadScripts();
        $this->_loadController();
        $this->_loadConfig();
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

        $this->publishes([
            __DIR__.'/../Resources/views' => resource_path('views/')
        ],'vordia-views');

    }

    private function _loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/migrations');

        $this->publishes([
            __DIR__.'/../Database/migrations' => database_path('migrations')
        ], 'vordia-migrations');
    }

    private function _loadScripts(): void
    {
        $this->publishes([
            __DIR__.'/../Resources/css' => public_path('/css')
        ], 'vordia-styles');

        $this->publishes([
            __DIR__.'/../Resources/fonts' => public_path('/fonts')
        ], 'vordia-fonts');

        $this->publishes([
            __DIR__.'/../Resources/js' => public_path('/js')
        ], 'vordia-scripts');

        $this->publishes([
            __DIR__.'/../Resources/webfonts' => public_path('/webfonts')
        ], 'vordia-scripts');
    }

    private function _loadController(): void
    {
        $this->publishes([
            __DIR__.'/../Http/Controllers' => app_path('Http/Controllers'),
        ], 'vordia-controllers');

        $this->publishes([
            __DIR__.'/../Http/Channels' => app_path('Http/Channels'),
        ], 'vordia-channels');

        $this->publishes([
            __DIR__.'/../Http/Notifications' => app_path('Http/Notifications'),
        ], 'vordia-notifications');
    }

    private function _loadConfig(): void
    {
        $this->publishes([
            __DIR__.'/../config' => config_path('/config'),
        ], 'vordia-config');
    }

}

