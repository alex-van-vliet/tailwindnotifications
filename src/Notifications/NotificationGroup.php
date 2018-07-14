<?php namespace AlexVanVliet\TailwindNotifications\Notifications;

use AlexVanVliet\TailwindNotifications\Notification;

class NotificationGroup extends Notification
{
    protected $notifications;

    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    public function render()
    {
        $str = '';
        foreach ($this->notifications as $notification) {
            $text = $notification->render();
            if ($notification->trim()) {
                $text = trim($text);
            }
            if ($notification->sanitize()) {
                $text = e($text);
            }
            if ($notification->lines()) {
                $text = nl2br($text);
            }
            $str .= $text;
        }
        return $str;
    }

    public function sanitize()
    {
        return false;
    }

    public function lines()
    {
        return false;
    }

    public function trim()
    {
        return false;
    }
}