<?php namespace AlexVanVliet\TailwindNotifications\Notifications;

class EscapedTextNotification extends TextNotification
{
    public function sanitize()
    {
        return false;
    }
}