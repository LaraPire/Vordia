<?php

namespace Rayiumir\Vordia\ServiceProvider;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
        $this->mergeConfigFrom(__DIR__ . '/../../config/vordia.php', 'vordia');

        if ($this->app->runningInConsole()) {

            $this->replaceUsersMigration();
            
        }
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
        $this->loadRoutesFrom(__DIR__ . '/../Routes/vordia.php');
    }

    /**
     * For Load Views
     *
     * @return void
     */
    private function _loadViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'Vordia');
    }

    private function _loadScripts(): void
    {
        $this->publishes([__DIR__ . '/../Resources/css' => public_path('/css')], 'vordia-styles');

        $this->publishes([__DIR__ . '/../Resources/fonts' => public_path('/fonts')], 'vordia-fonts');

        $this->publishes([__DIR__ . '/../Resources/js' => public_path('/js')], 'vordia-scripts');

        $this->publishes([__DIR__ . '/../Resources/webfonts' => public_path('/webfonts')], 'vordia-scripts');
    }

    private function _loadController(): void
    {
        $this->publishes([__DIR__ . '/../Http/Controllers' => app_path('Http/Controllers'),], 'vordia-controllers');

        $this->publishes([__DIR__ . '/../Http/Channels' => app_path('Http/Channels'),], 'vordia-channels');

        $this->publishes([__DIR__ . '/../Http/Notifications' => app_path('Http/Notifications'),], 'vordia-notifications');
    }

    private function _loadConfig(): void
    {
        $this->publishes([__DIR__ . '/../../config' => config_path('/'),], 'vordia-config');
    }

    protected function replaceUsersMigration(): void
    {
        $migrationsPath = database_path('migrations');
        $files = File::glob($migrationsPath . '/*_users_table.php');

        if (!empty($files)) {
            File::put($files[0], File::get(__DIR__.'/../Stubs/create_users_table.php.stub'));
            $this->info('Default users migration has been replaced with nullable version.');
        } else {
            $this->publishes([
                __DIR__.'/../Stubs/create_users_table.php.stub' => $this->getMigrationFilename('create_users_table.php'),
            ], 'migrations');
        }
    }

    protected function getMigrationFilename($migrationName): string
    {
        return database_path('migrations') . '/' . date('Y_m_d_His') . '_' . $migrationName;
    }

    protected function info($message): void
    {
        Log::info($message);
    }
}

