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

use Closure;
use Illuminate\View\Factory;

/**
 * Load the notifications from the session and share the notifications to the view.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class TailwindNotificationsMiddleware
{
    /**
     * The notifications.
     *
     * @var \AlexVanVliet\TailwindNotifications\Notifications
     */
    protected $notifications;

    /**
     * The view factory.
     *
     * @var \Illuminate\View\Factory
     */
    protected $view;

    /**
     * TailwindNotificationsMiddleware constructor.
     *
     * @param \AlexVanVliet\TailwindNotifications\Notifications $notifications The notifications.
     * @param \Illuminate\View\Factory                          $view          The view factory.
     */
    public function __construct(Notifications $notifications, Factory $view)
    {
        $this->notifications = $notifications;
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request The current request.
     * @param \Closure                 $next    The next action.
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->notifications->addFromSession();

        $this->view->share('notifications', $this->notifications);

        $response = $next($request);

        $this->notifications->putInSession();

        return $response;
    }
}
