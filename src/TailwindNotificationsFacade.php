<?php namespace AlexVanVliet\TailwindNotifications;

use Illuminate\Support\Facades\Facade;

class TailwindNotificationsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tailwindnotifications';
    }
}