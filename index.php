<?php

class AppleDevice
{
    // Properties
    // $RAM; //Error

    public $RAM; // public is a visability marker, visability markers are var, public, private & protected
    // var and public are the same, var is old and public is new
    public $screenSize;
    public $memory;
    public $color;
    private $lock; // private means that the instance can't access it directly, but from the public method
    private $humanBody; // Your body, you (instance) can't access it directly, but from the public method (like going to a doctor)
    private const os = 'iOS'; //with const, we don't use '$'


    // Methods
    public function sayHello()
    {
        echo 'Hello from Apple Device';
    }

    // This => $this, pseudo variable

    public function getSpecs()
    {
        return "
        ------------------Specs------------------ </br>
        RAM: $this->RAM </br>
        Memory: $this->memory </br>
        Color: $this->color </br>
        ------------------Specs------------------ </br>
        ";
    }

    // Constants
    const DEVICE_TYPE = 'iPhone';

    // With constants, we use self instead of $this, self refers to the class itself but $this referes to the instance
    // However, constants are public by default, so we can access them from outside the class using the '::' (scope resolution operator)

    public function getDeviceType()
    {
        return self::DEVICE_TYPE;
    }

    /**
     * self vs $this
     * 
     * self:
     * self refers to the class itself
     * self is used to access static members (properties and methods)
     * self doesn't use '$' because it's not a variable but representing the class construction
     * 
     * $this:
     * $this refers to the instance
     * $this is used to access non-static members (properties and methods)
     * $this uses '$' because it's a variable representing the instance
     * 
     */

     // Encapsulation
     public function changeLock($lock)
     {
        $this->lock = sha1($lock);
        return $this->lock;
     }

     public function treatment($illness)
     {
        $this->humanBody = "He was suffering from $illness";
        // we return $this->humanbody and not self::humanBody because humanBody is not a static property, i.e everyone (evey instance) has their own humanBody
        return $this;
     }

     private function getOs()
     {
        return self::os;
     
     }

     public function userGetOs()
     {
        return $this->getOs();
     }
}

// Inheritance
class SonyDevice extends AppleDevice // SonyDevice is a child class (subclass) of AppleDevice (super class)
{
    // Overriding Properties
    public $lock;
    // private $memory;
    public $camera;
    public const os = 'Android'; // constants can be overriden
    // On overriding, you can change the visability of the class member from private (in super) to public (in sub) but not the opposite

    // Overriding Methods
    public function changeLock($lock)
     {
        $this->lock = sha1($lock);
        return $this->lock;
     }

     // To prevent overriding, use final keyword
     final public function finalFun()
     {
        echo "You can never change this text";
     }

     public function getOs()
     {
        return self::os;
     }

}

$iPhone = new AppleDevice();
$iPhone->RAM = '4GB';
// $iPhone['screenSize'] = '6 inch'; ERROR
$memorySpace = 'memory';
$iPhone->$memorySpace = '64GB';
$iPhoneColor = 'color';
$iPhone->$iPhoneColor = 'black';
$iPhone->outsideProp = 'This is a new property'; // No Error, but unconsistent


$iPhone->sayHello();
echo '<br>';
echo '-----------------------------------------------------------------------<br>';
echo $iPhone->getSpecs();

echo '<pre>';
var_dump($iPhone);
echo '</pre>';


echo '<br>';
echo '-----------------------------------------------------------------------<br>';

echo 'From The instance</br>';
echo $iPhone::DEVICE_TYPE;
echo '<br></br></br>';
echo 'From The Class Name</br>';
echo AppleDevice::DEVICE_TYPE;
echo '<br></br></br>';

echo '-----------------------------------------------------------------------<br>';

$iPhone->changeLock('1234');

echo '<pre>';
var_dump($iPhone->treatment('headache'));
echo '</pre>';

echo '-----------------------------------------------------------------------<br>';

$sony = new SonyDevice();
print_r($sony);
echo '<br>';
echo $sony->usergetOs();

echo "-----------------------------------------------------------------------<br>";

abstract class bluePrint
{
    // abstract method without body (must be declared in the children)
    abstract public function printOut();

    // Normal method - bad practice
    public function printOut2()
    {
        echo "This is a normal method";
    }

    // public $message;
    abstract public function printOut3($message);
}