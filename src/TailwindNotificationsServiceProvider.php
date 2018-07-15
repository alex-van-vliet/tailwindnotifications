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
    public function boot()
    {
        $this->bootViews();

        $this->bootPublishedFiles();
    }

    /**
     * Add the views namespace.
     *
     * @return void
     */
    protected function bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../views', 'tailwindnotifications');
    }

    /**
     * Allow the published files to be extracted.
     *
     * @return void
     */
    protected function bootPublishedFiles()
    {
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
        $this->registerConfig();

        $this->registerNotifications();

        $this->registerHelpers();
    }

    /**
     * Merge the config file with the extracted one to provide default values.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tailwindnotifications.php', 'tailwindnotifications'
        );
    }

    /**
     * Register the notifications class.
     *
     * @return void
     */
    protected function registerNotifications()
    {
        $this->app->singleton(Notifications::class, function ($app) {
            return new Notifications(
                array_keys(config('tailwindnotifications.bags')),
                config('tailwindnotifications.bags'),
                $app->make(Session::class)
            );
        });
        $this->app->bind('tailwindnotifications', Notifications::class);
    }

    /**
     * Require the helpers.
     *
     * @return void
     */
    protected function registerHelpers()
    {
        require __DIR__ . '/helpers.php';
    }
}
