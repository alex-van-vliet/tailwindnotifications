<?php namespace AlexVanVliet\TailwindNotifications;

abstract class Notification
{
    abstract public function render();

    public function sanitize()
    {
        return true;
    }

    public function lines()
    {
        return true;
    }

    public function trim()
    {
        return true;
    }
}