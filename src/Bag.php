<?php namespace AlexVanVliet\TailwindNotifications;

use AlexVanVliet\TailwindNotifications\Notifications\I18nTextNotification;

class Bag
{
    protected $session;
    protected $name;
    protected $plural;
    protected $notifications;
    protected $html = [
        'bag' => [
            'start' => '',
            'end' => '',
        ],
        'notification' => [
            'start' => '',
            'end' => '',
        ],
    ];

    public function __construct($session, $name, $plural, $options = [])
    {
        $this->session = $session;
        $this->name = $name;
        $this->plural = $plural;
        $this->notifications = collect();
        if(isset($options['html'])){
            foreach (['bag', 'notification'] as $part) {
                if (isset($options['html'][$part])) {
                    foreach (['start', 'end'] as $position) {
                        if (isset($options['html'][$part][$position])) {
                            $this->html[$part][$position] = $options['html'][$part][$position];
                        }
                    }
                }
            }
        }
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
        $last = $this->notifications->count() - 1;
        if($last < 0) return '';
        $str = $this->html['bag']['start'];
        foreach ($this->notifications as $k => $notification) {
            $text = $notification->render();
            if ($notification->trim()) $text = trim($text);
            if ($notification->sanitize()) $text = e($text);
            if ($notification->lines()) $text = nl2br($text);

            $str .= $this->html['notification']['start'];
            $str .= $text;
            $str .= $this->html['notification']['end'];
        }
        $str .= $this->html['bag']['end'];
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