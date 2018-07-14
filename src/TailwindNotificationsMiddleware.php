<?php namespace AlexVanVliet\TailwindNotifications;

use Closure;
use Illuminate\Session\SessionManager;
use Illuminate\View\Factory;

class TailwindNotificationsMiddleware
{
    protected $notifications;
    protected $view;
    /**
     * @var SessionManager
     */
    private $session;

    public function __construct(Notifications $notifications, Factory $view)
    {
        $this->notifications = $notifications;
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->notifications->addFromSession();

        $this->view->share('notifications', $this->notifications);

        return $next($request);
    }
}
