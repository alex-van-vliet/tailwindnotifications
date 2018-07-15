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

/**
 * Handle HTML.
 *
 * @package   alex-van-vliet/tailwindnotifications
 * @author    Alex van Vliet <alex@vanvliet.pro>
 * @copyright 2018 Alex van Vliet
 * @license   https://github.com/alex-van-vliet/tailwindnotifications/license.md MIT
 * @link      https://github.com/alex-van-vliet/tailwindnotifications
 */
class HTMLHelper
{
    /**
     * The name of the bag.
     *
     * @var string
     */
    protected $name;

    /**
     * The HTML defaults.
     *
     * @var array
     */
    protected $default;

    /**
     * The HTML values.
     *
     * @var array
     */
    protected $values;

    /**
     * The themes.
     *
     * @var array
     */
    protected $themes;

    /**
     * The selected theme.
     *
     * @var string|null
     */
    protected $theme;

    /**
     * HTMLHelper constructor.
     *
     * @param string $name     The name of the bag.
     * @param array  $defaults The HTML defaults.
     * @param array  $values   The HTML values.
     * @param array  $themes   The themes.
     */
    public function __construct($name, $defaults, $values, $themes)
    {
        $this->name = $name;
        $this->default = $defaults;
        $this->values = $values;
        $this->themes = $themes;
        $this->theme = null;
    }

    /**
     * Select the theme.
     *
     * @param string|null $theme The theme to select.
     *
     * @return self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Handle getting a value.
     *
     * @param string $name      The name of the function called.
     * @param array  $arguments The arguments of the function called.
     *
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function __call($name, $arguments)
    {
        $count = count($arguments);
        if ($count === 1) {
            if ($this->theme) {
                if (isset($this->themes[$this->theme])
                    && isset($this->themes[$this->theme][$this->name])
                    && isset($this->themes[$this->theme][$this->name][$name])
                    && isset($this->themes[$this->theme][$this->name][$name][$arguments[0]])
                ) {
                    return $this->themes[$this->theme][$this->name][$name][$arguments[0]];
                }
            } else {
                if (isset($this->values[$name])
                    && isset($this->values[$name][$arguments[0]])
                ) {
                    return $this->values[$name][$arguments[0]];
                }
            }
            if (isset($this->default[$name])
                && isset($this->default[$name][$arguments[0]])
            ) {
                return $this->default[$name][$arguments[0]];
            }
        }

        throw new BadMethodCallException(
            "Could not call method $name with $count arguments."
        );
    }
}