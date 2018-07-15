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
use Exception;

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
     * @var array
     */
    protected $notifications;

    /**
     * The list of notifications to be flashed.
     *
     * @var array
     */
    protected $flashNotifications;

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
        $this->notifications = [];
        $this->flashNotifications = [];
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
            $this->notifications[] = $notification;
        } else {
            $this->notifications[] = new I18nTextNotification($notification);
        }
    }

    /**
     * Render the notifications.
     *
     * @return string
     */
    public function render()
    {
        $last = count($this->notifications) - 1;
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
     * Flash several notifications.
     *
     * @param array $notifications The notifications.
     *
     * @return void
     */
    public function flashSeveral($notifications)
    {
        foreach ($notifications as $notification) {
            $this->flash($notification);
        }
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
        $this->flashNotifications[] = $notification;
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
     * Flash the notifications to the session.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function putInSession()
    {
        if (!$this->flashNotifications) {
            return;
        }
        if ($this->plural) {
            $this->session->flash(
                "notifications.$this->plural", $this->flashNotifications
            );
        } else {
            if (count($this->flashNotifications) === 1) {
                $this->session->flash(
                    "notifications.$this->name", $this->flashNotifications[0]
                );
            } else {
                throw new Exception(
                    "Could not flash multiple notifications without '
                    .'a plural version of the name of the bag."
                );
            }
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
     * Whether the bag has any notifications.
     *
     * @return bool
     */
    public function hasNotifications()
    {
        return count($this->notifications) > 0;
    }
}