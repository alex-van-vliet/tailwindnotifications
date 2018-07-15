<?php
/**
 * PHP version 7
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */

namespace AlexVanVliet\TailwindNotifications;

use Illuminate\Contracts\Session\Session;
use Illuminate\Support\ServiceProvider;

/**
 * Register and boot required package services.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
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
        $this->publishes(
            [
                __DIR__ . '/../config/tailwindnotifications.php' => config_path(
                    'tailwindnotifications.php'
                ),
                __DIR__ . '/../views' => resource_path(
                    'views/vendor/tailwindnotifications'
                ),
            ]
        );
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
            __DIR__ . '/../config/tailwindnotifications.php',
            'tailwindnotifications'
        );
    }

    /**
     * Register the notifications class.
     *
     * @return void
     */
    protected function registerNotifications()
    {
        $this->app->singleton(
            Notifications::class,
            function ($app) {
                return new Notifications(
                    $app->make(Session::class),
                    array_keys(config('tailwindnotifications.bags')),
                    config('tailwindnotifications')
                );
            }
        );
        $this->app->bind('tailwindnotifications', Notifications::class);
    }

    /**
     * Require the helpers.
     *
     * @return void
     */
    protected function registerHelpers()
    {
        include __DIR__ . '/helpers.php';
    }
}
