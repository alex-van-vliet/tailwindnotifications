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

use AlexVanVliet\TailwindNotifications\Notifications\I18nTextNotification;

/**
 * A bag of notifications.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class Bag
{
    /**
     * The session store.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * The name of the bag.
     *
     * @var string
     */
    protected $name;

    /**
     * The plural version of the name of the bag.
     *
     * @var false|string
     */
    protected $plural;

    /**
     * The list of notifications.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $notifications;

    /**
     * The html helper.
     *
     * @var \AlexVanVliet\TailwindNotifications\HTMLHelper
     */
    protected $html;

    /**
     * Bag constructor.
     *
     * @param \Illuminate\Contracts\Session\Session $session The session store.
     * @param string                                $name    The name of the bag.
     * @param string|false                          $plural  The plural version of the name of the bag.
     * @param array                                 $options The options.
     */
    public function __construct($session, $name, $plural, $options = [])
    {
        $this->session = $session;
        $this->name = $name;
        $this->plural = $plural;
        $this->notifications = collect();
        $this->html = new HTMLHelper(
            [
                'bag' => [
                    'start' => '',
                    'end' => '',
                ],
                'notification' => [
                    'start' => '',
                    'end' => '',
                ],
            ]
        );
        if (isset($options['html'])) {
            $this->html->set($options['html']);
        }
    }

    /**
     * Get the plural version of the name of the bag.
     *
     * @return string|false
     */
    public function getPlural()
    {
        return $this->plural;
    }

    /**
     * Get the name of the bag.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Push several notifications.
     *
     * @param array $notifications The notifications.
     *
     * @return void
     */
    public function pushSeveral($notifications)
    {
        foreach ($notifications as $notification) {
            $this->push($notification);
        }
    }

    /**
     * Push a new notification.
     *
     * If the notification is a string, change it to an internationalized notification.
     *
     * @param string|\AlexVanVliet\TailwindNotifications\Notification $notification The notification.
     *
     * @return void
     */
    public function push($notification)
    {
        if ($notification instanceof Notification) {
            $this->notifications->push($notification);
        } else {
            $this->notifications->push(new I18nTextNotification($notification));
        }
    }

    /**
     * Render the notifications.
     *
     * @return string
     */
    public function render()
    {
        $last = $this->notifications->count() - 1;
        if ($last < 0) {
            return '';
        }
        $str = $this->html->bag('start');
        foreach ($this->notifications as $k => $notification) {
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

            $str .= $this->html->notification('start');
            $str .= $text;
            $str .= $this->html->notification('end');
        }
        $str .= $this->html->bag('end');
        return $str;
    }

    /**
     * Flash a notification.
     *
     * @param string|\AlexVanVliet\TailwindNotifications\Notification $notification The notification
     *
     * @return void
     */
    public function flash($notification)
    {
        $this->session->flash("notifications.$this->name", $notification);
    }

    /**
     * Flash several notifications.
     *
     * @param array $notifications The notifications.
     *
     * @return void
     */
    public function flashSeveral($notifications)
    {
        $this->session->flash("notifications.$this->plural", $notifications);
    }

    /**
     * Load the notifications from the session.
     *
     * @return void
     */
    public function addFromSession()
    {
        if ($this->session->has("notifications.$this->name")) {
            $this->push($this->session->get("notifications.$this->name"));
        }
        if ($this->plural) {
            if ($this->session->has("notifications.$this->plural")) {
                foreach (
                    $this->session->get("notifications.$this->plural") as
                    $notification
                ) {
                    $this->push($notification);
                }
            }
        }
    }

    /**
     * Whether the bag has any notifications.
     *
     * @return bool
     */
    public function hasNotifications()
    {
        return $this->notifications->count() > 0;
    }
}