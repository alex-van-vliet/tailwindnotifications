<?php namespace AlexVanVliet\TailwindNotifications\Notifications;

use AlexVanVliet\TailwindNotifications\Notification;

class I18nTextNotification extends Notification
{
    protected $text;
    protected $params;

    public function __construct($text, $params = [])
    {
        if (is_array($text)) {
            $this->text = array_shift($text);
            $this->params = array_merge(array_shift($text) ?: [], $params);
        } else {
            $this->text = $text;
            $this->params = $params;
        }
    }

    public function render()
    {
        return trans($this->text, $this->params);
    }
}