<?php

class ServiceContainer
{
    private static $services = [];


    public function bind($name, $service)
    {
        self::$services[$name] = $service;
        return $this;
    }

    public function make($name)
    {
        return self::$services[$name];
    }
}
