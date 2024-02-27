<?php

use ServiceContainer;

class DummyFacade
{
    protected static $dummyFacade = 'test';

    public function sayHello()
    {
        echo "Hello from the DummyFacade";
    }

    /**
     * This function will be triggered when invoking a non-existing static method in the DummyFacade class
     * @param $name method name
     * @param $arguments method arguments
     * */
    public static function __callStatic($name, $arguments)
    {
        $sc = new ServiceContainer();
        $sc->make('DummyFacade', self::$dummyFacade);
    }
}
