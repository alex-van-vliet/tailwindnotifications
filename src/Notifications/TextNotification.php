<?php namespace AlexVanVliet\TailwindNotifications\Notifications;

use AlexVanVliet\TailwindNotifications\Notification;

class TextNotification extends Notification
{
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function render()
    {
        return $this->text;
    }
}