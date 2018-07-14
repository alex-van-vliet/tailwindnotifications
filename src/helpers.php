<?php

function flash($bag, ...$params)
{
    Notifications::{'flash' . ucfirst($bag)}(...$params);
}

function instant($bag, ...$params)
{
    Notifications::{$bag}(...$params);
}