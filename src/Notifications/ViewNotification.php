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
 * View notification.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class ViewNotification extends Notification
{
    /**
     * The view to load.
     *
     * @var string
     */
    protected $view;

    /**
     * The parameters to send to the view.
     *
     * @var array
     */
    protected $params;

    /**
     * ViewNotification constructor.
     *
     * @param string $view   The view to load.
     * @param array  $params The parameters to send to the view.
     */
    public function __construct($view, $params = [])
    {
        $this->view = $view;
        $this->params = $params;
    }

    /**
     * Render the notification.
     *
     * @return string
     */
    public function render()
    {
        return view($this->view, $this->params);
    }
}