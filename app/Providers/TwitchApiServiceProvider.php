<?php

namespace App\Providers;

use App\Services\TwitchClientApi;
use App\Services\TwitchUserApi;
use Illuminate\Support\ServiceProvider;

class TwitchApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TwitchClientApi::class, function ($app) {
            return new TwitchClientApi($app['config']['services']['twitch']);
        });

        $this->app->singleton(TwitchUserApi::class, function ($app) {
            return new TwitchUserApi($app['config']['services']['twitch']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [TwitchClientApi::class, TwitchUserApi::class];
    }
}
