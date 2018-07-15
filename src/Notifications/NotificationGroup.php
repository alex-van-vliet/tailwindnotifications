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

namespace AlexVanVliet\TailwindNotifications\Notifications;

use AlexVanVliet\TailwindNotifications\Notification;

/**
 * Group several notifications together.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class NotificationGroup extends Notification
{
    /**
     * The notifications to group.
     *
     * @var array
     */
    protected $notifications;

    /**
     * NotificationGroup constructor.
     *
     * @param array $notifications The notifications to group.
     */
    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Render the notification.
     *
     * @return string
     */
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

    /**
     * Whether the function e should be applied on the text.
     *
     * @return bool
     */
    public function sanitize()
    {
        return false;
    }

    /**
     * Whether the function nl2br should be applied on the text.
     *
     * @return bool
     */
    public function lines()
    {
        return false;
    }

    /**
     * Whether the function trim should be applied on the text.
     *
     * @return bool
     */
    public function trim()
    {
        return false;
    }
}