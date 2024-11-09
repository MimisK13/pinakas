<?php

namespace Mimisk13\Pinakas;

use Illuminate\Support\ServiceProvider;

class PinakasServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'mimisk13');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'pinakas');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pinakas.php', 'pinakas');

        // Register the service the package provides.
        $this->app->singleton('pinakas', function () {
            return new Pinakas;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pinakas'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/pinakas.php' => config_path('pinakas.php'),
        ], 'pinakas-config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/pinakas'),
        ], 'pinakas-views');

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/mimisk13'),
        ], 'pinakas.assets');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/mimisk13'),
        ], 'pinakas.lang');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
