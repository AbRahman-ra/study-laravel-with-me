# 02 - PHP OOP, Namespaces and PSR-4 Autoload Quick Start

## OOP

- Access Modifiers
  - `public`: can be accessed from anywhere (outside the instance)
  - `protected`: can be accessed from the class and its subclasses only
  - `private`: can be accessed from the instance only
  - `var`: same as `public` but ended since PHP 4 (still supported but no one use it)
  - More on this later with Elzero OOP course
- In PHP OOP, we use `->` instead of `.` because `.` in PHP is already used for concatenation
- Inside the class methods, we usually return `$this` to allow object chaining

```php
$person = new Person();
$person->setName('Ahmed')->changePassword('1234567890')->createBlaBla('test');
```

- `setName`, `changePassword` & `createBlaBla` are all methods inside the `Person` class. so we return `$this` to allow reusing the instance again in different methods
- Properties must have an access , while methods don't (their default is set to `public`)
- Properties values are either null or a primitive data, to use non-primitive data we can define the variables inside the constructor function
- We can't use `$this` keyword with static method. We use `self` instead. Also, we use the `$` for property names associated with self
  - We put the `$` after the `self` to distinguish between the static property and the constant
  - The difference between `static` property and `const` property is that I can change the `static` value but I can't change the `const` value (both are class members not instance members, i.e. static)
- To access static properties we can do this using two ways
  - `ClassName::propertyName`
  - `$instance::$propertyName` (if any of both handsides is static, we directly use the scope resolution), this approach is rare in use
- Now, consider this code

```php
$person = new Person();
$person::$eyeColor = 'green';
$person2 = new Person; // you can write it both ways (with & without parantheses)
$person2::$eyeColor = 'blue';

echo $person::$eyeColor;
```

- The output is `blue` not `green` because it's a static property, no matter who changed it, it will be changed for all instances (class based)

```php
class Person
{
    private static $password;
    public function changePassword(string $password)
    {
        self::$password = $password;
        return $this;
    }

    public function getPassword()
    {
        return sha1(self::$password);
    }
}
```

## Including a File in PHP

- The difference between `include` & `require` is that when `include` doesn't find the file, it gives a warning and then completes the execution of the rest of the code. But `require` exits the app with a fetal error
- The magic constants `__DIR__`, `__FILE__`, `__LINE__`, `DIRECTORY_SEPARATOR`, etc... are called magic because they are constants but their value changes depending on their position
- Assume we want to include two classes that have the same name in the same `app.php` file
  - PHP will not allow us to do so
  - Motivation for namespaces
  - we write in the beginning of the code `namespace X`
  - This allows us to define classes, constants & functions with the same name without conflicts
  - If namespace is not mentioned, it's set to public (global) namespace
- To include a constant in PHP there are two ways
  - `define('NAME', value);`
  - `const NAME = value;`
  - The difference is that `define` always set the name into the global namespace. While `const` sets the constant in the cutrrent namespace
- To instantiate classes inside namespaces you need to use `<NameSpace>\<Class>` instead of just the class name `$person = new A\Person();`
- Assume you are in a namespace `C`. You want to call the classes in namespaces `A` & `B`. Let's see the code

```php
namespace C;

include "./Person.php"; // Person Class inside namespace A
include "./../php2/Person.php"; // Person Class inside namespace B

$person = new A\Person(); // FETAL ERROR
```

The error is because PHP thinks that `A` is a namespace lying under `C` which is not true. To specify that the namespace is not a part of the current namespace, you need to add `\` in the beginning, to tell the program to start from the global namespace.

```php
$person = new \A\Person();
```

This is very similar to linux when you're inside a folder (say `Downloads`), and you want to go to `home`, so you wrote

```bash
cd home
```

You will get an error because the executer is seeking a folder called `home` inside the current directory (relative path). To specifiy the real `home` directory (absolute path), you correct the code by writing

```bash
cd /home
```

- To import the whole namespace at the beginning, we can write the `use` keyword
  - No need to start with `\` with `use`. It always starts from the global namespace
    
  ```php
  use A\Person;
  $person = new Person(); // Person class in namespace A
  ```
- If we imported 2 (or more) namespaces with the same calss names in the same file, we can avoid this by aliasing

```php
namespace C;

use A\Person;
use B\Person as PersonB;

$person = new Person();
$person2 = new PersonB();
```

- We can use functions or constants and not only classes by writing `use function <nameSpace>\<functionOrConstName>`
- PHP looks for functions and constants in the current namespace, if not found, it will look for it in the global namespace.
- However, in classes, PHP looks for the classes only in the current namespace, if it's not found, it will throw error
- To import a class from the global namespace, write `\` before its name in its instantiation, or write `use ClassName` to load it

## Autoload

- PSR (PHP Standard Recommendations)
- Using the PSR-7, let's simulate the autoload file

```php
function load_class($className)
{
  include __DIR__.DIRECTORY_SEPARATOR.$className.'.php';
}

spl_autoload_register('load_class');
```

- `spl_autoload_register` is a function that registeres a class loader in the `autoload.php` file.
- We can pass the callback in multiple ways
  - The whole function without name as a closure argument

  ```php
  spl_autoload_register(function ($className) {
    include __DIR__.DIRECTORY_SEPARATOR.$className.'.php';
  });
  ```

  - The function name as a string
  - An array containing the class instance and the method name (`[classInstance, 'methodName']`)
  - If the method is static, the same array but the first item is just the class name as a string (`['<nameSpace>\className', 'StaticMethodName']`)
  - Using class name with the special constant (`[ClassName::class, 'methodName']`)
    - `ClassName::class` is returning the class name with respect to the namespace as a string
- Auto-loading happens only for calsses, interfaces & traits. Functions & constants have no auto-loading
- Traits are blocks that contains only methods and can be shared among classes
- Multiple traits can be used in the same class, PHP doesn't allow multiple inheritence
- The same trait can be also shared among multiple classes

```php
Trait info
{
    public function getDetails(){
        return $this->details;
    }
    public function setDetails($newDetails)
    {
        $this->details = $newDetails;
        return $this;
    }
}
```

- Traits are imported with the keyword `use` very similar to the classes, they are used inside the class using `use <Trait>`. They can be overridden as well

## Interfaces

- Interfaces are autoloaded
- Interfaces can't include properties (only methods)
- Interfaces' members are always public (can't be private or protected)

## Polymorphism

- If we have a parent class and a child class. Each of them have a method but their implementation is different. If I want to trigger the method with the parent implementation inside the child class we use the `parent` keyword

```php
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
        $helloParent = parent::sayHello();
        echo "Hello from the Parent";
    }
}
```

- We can use another keyword `static`, that looks for member in `self`. If it's not found, it looks for it in `parent`, then in children. If you want to access a value in the child class you must use `static`

```php
public static function sayHello()
    {
        $hello = static::sayHello(); // looks for self, then parent then children
        echo "Hello from the Parent";
    }
```