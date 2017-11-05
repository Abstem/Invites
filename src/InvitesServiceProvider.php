<?php

namespace Abstem\Invites;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class InvitesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__ . '/config/invites.php' => config_path('invites.php'),
        ], 'invites_config');

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/translations', 'Invites');

        $this->publishes([
            __DIR__ . '/resources/lang' => resource_path('lang/vendor/Invites'),
        ]);

        $this->publishes([
            __DIR__ . '/resources/assets' => public_path('vendor/Invites'),
        ], 'invites_assets');

        if ($this->app->runningInConsole()) {
            $this->commands([
                /* List Of Commands Here */
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/invites.php', 'invites'
        );
    }
}
