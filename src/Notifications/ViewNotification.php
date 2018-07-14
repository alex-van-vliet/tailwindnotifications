<?php namespace AlexVanVliet\TailwindNotifications\Notifications;

use AlexVanVliet\TailwindNotifications\Notification;

class ViewNotification extends Notification
{
    protected $view;
    protected $params;

    public function __construct($view, $params = [])
    {
        $this->view = $view;
        $this->params = $params;
    }

    public function render()
    {
        return view($this->view, $this->params);
    }
}