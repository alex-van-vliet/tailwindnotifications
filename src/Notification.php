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

/**
 * Base class for each notification.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
abstract class Notification
{
    /**
     * Render the notification.
     *
     * @return string
     */
    abstract public function render();

    /**
     * Whether the function e should be applied on the text.
     *
     * @return bool
     */
    public function sanitize()
    {
        return true;
    }

    /**
     * Whether the function nl2br should be applied on the text.
     *
     * @return bool
     */
    public function lines()
    {
        return true;
    }

    /**
     * Whether the function trim should be applied on the text.
     *
     * @return bool
     */
    public function trim()
    {
        return true;
    }
}