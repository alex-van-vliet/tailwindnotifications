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
 * Simple text notification.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class TextNotification extends Notification
{
    /**
     * The text to show.
     *
     * @var string
     */
    protected $text;

    /**
     * TextNotification constructor.
     *
     * @param string $text The text to show.
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Render the notification.
     *
     * @return string
     */
    public function render()
    {
        return $this->text;
    }
}