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

use BadMethodCallException;
use Exception;

/**
 * Handle all the notification bags.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class Notifications
{
    /**
     * The singular names of all the bags.
     *
     * @var array
     */
    protected $names;

    /**
     * The bags with their singular name as key.
     *
     * @var array
     */
    protected $bags;

    /**
     *  The plural version of the bag names.
     *
     * @var array
     */
    protected $plurals;

    /**
     * Notifications constructor.
     *
     * @param \Illuminate\Contracts\Session\Session $session The session store.
     * @param array                                 $bags    The list of all the singular bag names.
     * @param array                                 $options The options for all the bags with the singular bag name as key and the options as value.
     *
     * @throws \Exception The plural version of the bag name is the same as the singular version.
     */
    public function __construct($session, $bags, $options)
    {
        $this->names = $bags;
        $this->bags = [];
        $this->plurals = [];
        foreach ($this->names as $singular) {
            if (isset($options['bags'][$singular])
                && isset($options['bags'][$singular]['plural'])
            ) {
                $plural = $options['bags'][$singular]['plural'];
            } else {
                $plural = str_plural($singular);
            }

            if ($plural) {
                if ($plural === $singular) {
                    throw new Exception(
                        "Plural must be different from singular name "
                        . "($singular). Please change the plural for "
                        . "'$singular' in the configuration file or set it to "
                        . "false to disable pluralization for this bag."
                    );
                }
                $this->plurals[$plural] = $singular;
            }
            $this->bags[$singular] = new Bag(
                $session,
                $singular,
                $plural,
                $options['bags'][$singular] ?? [],
                $options['themes'] ?? []
            );
        }
    }

    /**
     * Check if one bag has a notification.
     *
     * @return bool
     */
    public function hasNotifications()
    {
        foreach ($this->bags as $bag) {
            if ($bag->hasNotifications()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Produce the html for all the bags.
     *
     * @param null|string $theme The theme to apply.
     *
     * @return string
     */
    public function render($theme = null)
    {
        $str = '';
        foreach ($this->bags as $bag) {
            $str .= $bag->render($theme);
        }
        return $str;
    }

    /**
     * Handles instant notification and flash shortcuts.
     *
     * @param string $name      The name of the function called.
     * @param array  $arguments The arguments of the function called.
     *
     * @throws \BadMethodCallException
     *
     * @return $this|\AlexVanVliet\TailwindNotifications\Bag
     */
    public function __call($name, $arguments)
    {
        if (in_array($name, $this->getBagNames())) {
            if (empty($arguments)) {
                return $this->select($name);
            }
            return $this->instant($name, ...$arguments);
        }
        if ($this->getSingular($name) && !empty($arguments)) {
            return $this->instant($name, ...$arguments);
        }
        if (starts_with($name, 'flash') && !empty($arguments)) {
            $name = lcfirst(substr($name, 5));

            if ($name) {
                if (in_array($name, $this->getBagNames())) {
                    return $this->flash($name, ...$arguments);
                }
                if ($this->getSingular($name)) {
                    return $this->flash($name, ...$arguments);
                }
            }
        }

        $count = count($arguments);
        throw new BadMethodCallException(
            "Could not call method $name with $count arguments."
        );
    }

    /**
     * Get all the existing bag names.
     *
     * @return array
     */
    public function getBagNames()
    {
        return $this->names;
    }

    /**
     * Select a bag.
     *
     * @param string $singular The singular bag name.
     *
     * @throws \BadMethodCallException The bag does not exist.
     *
     * @return \AlexVanVliet\TailwindNotifications\Bag
     */
    public function select($singular)
    {
        if (in_array($singular, $this->getBagNames())) {
            return $this->bags[$singular];
        }

        throw new BadMethodCallException("Bag $singular does not exist.");
    }

    /**
     * Handles instant notifications.
     *
     * @param string $bag          The bag name.
     * @param array  ...$arguments The notifications.
     *
     * @throws \BadMethodCallException
     * @throws \Exception
     *
     * @return $this
     */
    public function instant($bag, ...$arguments)
    {
        if (empty($arguments)) {
            throw new BadMethodCallException(
                "Could not call method "
                . "instant with 1 parameters."
            );
        }
        if (in_array($bag, $this->getBagNames())) {
            switch (count($arguments)) {
            case 1:
                $this->select($bag)->push($arguments[0]);
                break;
            default:
                $this->select($bag)->push($arguments);
                break;
            }
        } elseif ($bag = $this->getSingular($bag)) {
            switch (count($arguments)) {
            case 1:
                $this->select($bag)->pushSeveral(
                    is_array($arguments[0]) ? $arguments[0] : $arguments
                );
                break;
            default:
                $this->select($bag)->pushSeveral($arguments);
                break;
            }
        } else {
            throw new Exception("Bag $bag does not exist.");
        }
        return $this;
    }

    /**
     * Get the singular version of a bag name.
     *
     * @param string $plural The plural version of the bag name.
     *
     * @return string|null
     */
    public function getSingular($plural)
    {
        return $this->plurals[$plural] ?? null;
    }

    /**
     * Handles flash notifications.
     *
     * @param string $bag          The bag name.
     * @param array  ...$arguments The notifications.
     *
     * @throws \BadMethodCallException
     * @throws \Exception
     *
     * @return $this
     */
    public function flash($bag, ...$arguments)
    {
        if (empty($arguments)) {
            throw new BadMethodCallException(
                "Could not call method "
                . "flash with 1 parameters."
            );
        }
        if (in_array($bag, $this->getBagNames())) {
            switch (count($arguments)) {
            case 1:
                $this->select($bag)->flash($arguments[0]);
                break;
            default:
                $this->select($bag)->flash($arguments);
                break;
            }
        } elseif ($bag = $this->getSingular($bag)) {
            switch (count($arguments)) {
            case 1:
                $this->select($bag)->flashSeveral(
                    is_array($arguments[0]) ? $arguments[0] : $arguments
                );
                break;
            default:
                $this->select($bag)->flashSeveral($arguments);
                break;
            }
        } else {
            throw new Exception("Bag $bag does not exist.");
        }
        return $this;
    }

    /**
     * Fetch the notifications from the session.
     *
     * @return void
     */
    public function addFromSession()
    {
        foreach ($this->getBagNames() as $bagName) {
            $this->select($bagName)->addFromSession();
        }
    }

    /**
     * Flash the notifications to the session.
     *
     * @return void
     */
    public function putInSession()
    {
        foreach ($this->getBagNames() as $bagName) {
            $this->select($bagName)->putInSession();
        }
    }

    /**
     * Get the plural version of a bag name.
     *
     * @param string $singular The singular version of the bag name.
     *
     * @return string|null
     */
    public function getPlural($singular)
    {
        return $this->bags[$singular]->getPlural() ?: null;
    }
}