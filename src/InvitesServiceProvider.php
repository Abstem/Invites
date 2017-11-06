<?php

namespace Abstem\Invites;

use Abstem\Invites\Classes\Invites;
use Abstem\Invites\Console\Commands\CleanupCommand;
use Illuminate\Support\ServiceProvider;

class InvitesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CleanupCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__ . '/../resources/config/invites.php' => config_path('invites.php'),
        ], 'invites_config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/Invites'),
        ]);

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('vendor/Invites'),
        ], 'invites_assets');

        $this->loadMigrationsFrom(__DIR__ . '/../resources/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/translations', 'Invites');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../resources/config/invites.php', 'invites'
        );

        $this->app->bind('invites', Invites::class);
        $this->app->singleton(Invites::class, Invites::class);
    }
}
