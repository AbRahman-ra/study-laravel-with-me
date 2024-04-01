<?php

namespace B;

class Person
{
    public $name;
    public $gender = 1 + 1 + 1 . "wasd";
    const MALE = 'm';
    private static $password;
    private static $numberOfPpl = 6543;
    public static $eyeColor = 'brown';

    public function changePassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return sha1(self::$password);
    }

    public function getGender()
    {
        return self::MALE;
    }

    public static function getNumberOfPpl()
    {
        return self::$numberOfPpl;
    }
}
