<?php namespace AlexVanVliet\TailwindNotifications;

use AlexVanVliet\TailwindNotifications\Notifications\I18nTextNotification;

class Bag
{
    protected $session;
    protected $name;
    protected $plural;
    protected $notifications;
    protected $function = null;

    public function __construct($session, $name, $plural, $options = [])
    {
        $this->session = $session;
        $this->name = $name;
        $this->plural = $plural;
        $this->notifications = collect();
    }

    public function getPlural()
    {
        return $this->plural;
    }

    public function getName()
    {
        return $this->name;
    }

    public function pushSeveral($notifications)
    {
        foreach ($notifications as $notification) {
            $this->push($notification);
        }
    }

    public function push($notification)
    {
        if ($notification instanceof Notification) {
            $this->notifications->push($notification);
        } else {
            $this->notifications->push(new I18nTextNotification($notification));
        }
    }

    public function render()
    {
        if (!$this->function) {
            $f = function ($notification, $first, $last) {
                $text = $notification->render();
                if ($notification->trim()) $text = trim($text);
                if ($notification->sanitize()) $text = e($text);
                if ($notification->lines()) $text = nl2br($text);
                return '<p>' . $text . '</p>';
            };
        } else {
            $f = $this->function;
        }
        $str = '';
        $last = $this->notifications->count() - 1;
        foreach ($this->notifications as $k => $notification) {
            $str .= $f($notification, $k === 0, $k === $last);
        }
        return $str;
    }

    public function flash($notification)
    {
        $this->session->flash("notifications.$this->name", $notification);
    }

    public function flashSeveral($notifications)
    {
        $this->session->flash("notifications.$this->plural", $notifications);
    }

    public function addFromSession()
    {
        if ($this->session->has("notifications.$this->name")) {
            $this->push($this->session->get("notifications.$this->name"));
        }
        if ($this->plural) {
            if ($this->session->has("notifications.$this->plural")) {
                foreach ($this->session->get("notifications.$this->plural") as $notification) {
                    $this->push($notification);
                }
            }
        }
    }

    public function hasNotifications()
    {
        return $this->notifications->count() > 0;
    }
}