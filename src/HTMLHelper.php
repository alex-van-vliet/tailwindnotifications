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
     * The HTML values.
     *
     * @var array
     */
    protected $values;

    /**
     * HTMLHelper constructor.
     *
     * @param array $defaults The HTML defaults.
     */
    public function __construct($defaults)
    {
        $this->values = $defaults;
    }

    /**
     * Set the values.
     *
     * @param array $values The new HTML values.
     *
     * @return void
     */
    public function set($values)
    {
        foreach ($this->values as $part => $positions) {
            if (isset($values[$part])) {
                foreach ($positions as $position => $value) {
                    if (isset($values[$part][$position])) {
                        $this->values[$part][$position]
                            = $values[$part][$position];
                    }
                }
            }
        }
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
            if (isset($this->values[$name])
                && isset($this->values[$name][$arguments[0]])
            ) {
                return $this->values[$name][$arguments[0]];
            }
        }

        throw new BadMethodCallException(
            "Could not call method $name with $count arguments."
        );
    }
}