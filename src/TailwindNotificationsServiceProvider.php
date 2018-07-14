<?php

namespace AlexVanVliet\TailwindNotifications;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;

class TailwindNotificationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Notifications $notifications)
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'tailwindnotifications');

        $this->publishes([
            __DIR__ . '/../config/tailwindnotifications.php' => config_path('tailwindnotifications.php'),
            __DIR__ . '/../views' => resource_path('views/vendor/tailwindnotifications'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tailwindnotifications.php', 'tailwindnotifications'
        );

        $this->app->singleton(Notifications::class, function ($app) {
            return new Notifications(
                array_keys(config('tailwindnotifications.bags')),
                config('tailwindnotifications.bags'),
                $app->make(Session::class)
            );
        });
        $this->app->bind('tailwindnotifications', Notifications::class);
        require __DIR__ . '/helpers.php';
    }
}
