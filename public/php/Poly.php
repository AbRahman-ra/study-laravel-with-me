<?php

interface generation
{
    function sayHello();
}

class Super
{
    public static function sayHello()
    {
        echo "Hello from the Parent";
    }
}

class Sub extends Super
{

    public static function sayHello()
    {
        $hello = parent::sayHello();
        echo "Hello from the Parent";
    }
}
