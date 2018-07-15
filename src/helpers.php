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

/**
 * Flash helper.
 *
 * @param string $bag       The bag in which to flash the notification.
 * @param array  ...$params The parameters to pass to the flash method.
 *
 * @return void
 */
function flash($bag, ...$params)
{
    Notifications::flash($bag, ...$params);
}

/**
 * Instant helper.
 *
 * @param string $bag       The bag in which to put the notification.
 * @param array  ...$params The parameters to pass to the method.
 *
 * @return void
 */
function instant($bag, ...$params)
{
    Notifications::instant($bag, ...$params);
}