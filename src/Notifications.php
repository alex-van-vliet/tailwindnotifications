<?php namespace AlexVanVliet\TailwindNotifications;

use BadMethodCallException;
use Exception;

class Notifications
{
    /** @var string[] The singular names of all the bags. */
    protected $names;

    /** @var \AlexVanVliet\TailwindNotifications\Bag[string] The bags with their singular name as key. */
    protected $bags;

    /** @var string[string] The plural version of */
    protected $plurals;

    /**
     * Notifications constructor.
     *
     * @param string[] $bags The list of all the singular bag names.
     * @param array[string] $options The options for all the bags with the singular bag name as key and the options as value.
     * @param \Illuminate\Contracts\Session\Session $session The session store.
     *
     * @throws \Exception The plural version of the bag name is the same as the singular version.
     */
    public function __construct($bags, $options, $session)
    {
        $this->names = $bags;
        $this->bags = [];
        $this->plurals = [];
        /** @var string $singular The bag singular name. */
        foreach ($this->names as $singular) {
            /** @var string $plural The bag plural name. */
            if (isset($options[$singular]) && isset($options[$singular]['plural'])) {
                $plural = $options[$singular]['plural'];
            } else {
                $plural = str_plural($singular);
            }

            if ($plural) {
                if ($plural === $singular) {
                    throw new Exception("Plural must be different from singular name ($singular). Please change the plural for '$singular' in the configuration file or set it to false to disable pluralization for this bag.");
                }
                $this->plurals[$plural] = $singular;
            }
            $this->bags[$singular] = new Bag($session, $singular, $plural, $options[$singular] ?? []);
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
     * @return string
     */
    public function render()
    {
        $str = '';
        foreach ($this->bags as $bag) {
            $str .= $bag->render();
        }
        return $str;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, $this->getBagNames())) {
            switch (count($arguments)) {
                case 0:
                    $this->select($name);
                    return $this;
                case 1:
                    $this->select($name)->push(reset($arguments));
                    return $this;
                default:
                    $this->select($name)->push($arguments);
                    return $this;
            }
        } else if (($bag = $this->getSingular($name)) && !empty($arguments)) {
            switch (count($arguments)) {
                case 1:
                    $this->select($bag)->pushSeveral(is_array($arguments[0]) ? $arguments[0] : $arguments);
                    return $this;
                default:
                    $this->select($bag)->pushSeveral($arguments);
                    return $this;
            }
        }
        if (starts_with($name, 'flash')) {
            $name = lcfirst(substr($name, 5));

            if ($name) {
                if (in_array($name, $this->getBagNames()) && !empty($arguments)) {
                    switch (count($arguments)) {
                        case 1:
                            $this->select($name)->flash(reset($arguments));
                            return $this;
                        default:
                            $this->select($name)->flash($arguments);
                            return $this;
                    }
                } else if (($bag = $this->getSingular($name)) && !empty($arguments)) {
                    switch (count($arguments)) {
                        case 1:
                            $this->select($bag)->flashSeveral(is_array($arguments[0]) ? $arguments[0] : $arguments);
                            return $this;
                        default:
                            $this->select($bag)->flashSeveral($arguments);
                            return $this;
                    }
                }
            }
        }

        $count = count($arguments);
        throw new BadMethodCallException("Could not call method $name with $count arguments.");
    }

    /**
     * Get all the existing bag names.
     *
     * @return string[]
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
     * @throws BadMethodCallException The bag does not exist.
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