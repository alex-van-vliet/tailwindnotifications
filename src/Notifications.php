<?php namespace AlexVanVliet\TailwindNotifications;

use BadMethodCallException;
use Exception;

class Notifications
{
    protected $names;
    protected $bags;
    protected $session;
    protected $plurals;
    protected $flasher;

    public function __construct($bags, $options, $session)
    {
        $this->session = $session;
        $this->names = $bags;
        $this->bags = [];
        $this->plurals = [];
        foreach ($this->names as $bag) {
            if (isset($options[$bag]['plural'])) {
                $plural = $options[$bag]['plural'];
            } else {
                $plural = str_plural($bag);
            }

            if ($plural) {
                if ($plural === $bag) {
                    throw new Exception("Plural must be different from bag name ($bag). Please change the plural for '$bag' in the configuration file or set it to false to disable pluralization for this bag.");
                }
                $this->plurals[$plural] = $bag;
            }
            $this->bags[$bag] = new Bag($this->session, $bag, $plural, $options[$bag] ?? []);
        }
    }

    public function hasNotifications()
    {
        foreach ($this->bags as $bag) {
            if ($bag->hasNotifications()) {
                return true;
            }
        }
        return false;
    }

    public function render()
    {
        $str = '';
        foreach ($this->bags as $bag) {
            $str .= $bag->render();
        }
        return $str;
    }

    public function flash()
    {
        return $this->flasher;
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

    public function getBagNames()
    {
        return $this->names;
    }

    public function select($bagName)
    {
        if (in_array($bagName, $this->getBagNames())) {
            return $this->bags[$bagName];
        }

        throw new BadMethodCallException("Bag $bagName does not exist.");
    }

    public function getSingular($plural)
    {
        return $this->plurals[$plural] ?? null;
    }

    public function addFromSession()
    {
        foreach ($this->getBagNames() as $bagName) {
            $this->select($bagName)->addFromSession();
        }
    }

    public function getPlural($singular)
    {
        return $this->bags[$singular]->getPlural() ?: null;
    }
}