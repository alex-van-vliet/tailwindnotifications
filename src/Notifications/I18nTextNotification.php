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
 * Internationalized text notification.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class I18nTextNotification extends Notification
{
    /**
     * The i18n key.
     *
     * @var string $text
     */
    protected $text;

    /**
     * The i18n parameters.
     *
     * @var array
     */
    protected $params;

    /**
     * I18nTextNotification constructor.
     *
     * There are two ways to use this class :
     * - use one parameter as an array, with the first element being the key and the second element being the parameters.
     * - use two parameters, with the first one being the key and the second one being the parameters.
     *
     * @param string|array $text   The text or an array (see desc.).
     * @param array        $params The parameters.
     */
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

    /**
     * Render the notification.
     *
     * @return string
     */
    public function render()
    {
        return trans($this->text, $this->params);
    }
}