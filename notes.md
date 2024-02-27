# PHP OOP

## Requirements

- Basic Knowledge of PHP with any language (I know JS OOP)

---

## Class Members (Properties & Methods)

```php
class Name
{
    $RAM; //Error
    public $RAM;
    private $humanBody;
}
```

- `public` is a visability marker, visability markers are `var`, `public`, `private` & `protected`
- `var` and `public` are the same, `var` is old and `public` is new
- `private` means that the instance can't access it directly, but from the public method
  - `$humanBody` is your body that you (instance) can't access it directly, but from the public method (like going to a doctor)

## Constants

```php
const DEVICE_TYPE = 'iPhone';
```

- With constants, we access them using `self` instead of `$this`, `self` refers to the class itself but `$this` referes to the instance
- `$this` is called a pseudo variable
- With constants, we don't use `$` since `$` is for variables
- However, constants are public by default, so we can access them from outside the class using the `::` (scope resolution operator)

## Private vs Static Members

- Private member means it cannot be accessed from outside of the instance
  - Your blood is private, no one can access it (even yourself) unless you use a public member that helps you with that (`goToDoctor()` method)
  - The data (class member) on your mobile (instance) is private, no one can access it unless they/you use a public member that helps you with that (`unlock()` method)
- Static member means it's a class member and not an instance member
  - A factory (class) is making mobile phones (instances). You can't know the number of the phones made through the instance, because this is a class data not a phone data

## Self vs This

| Point of Compariosn 🔽 | Self | This |
| ---------- | --- | ----------- |
| Syntax | `self` | `$this` |
| Reference | Refers to the class itself | Refers to the instance
| Access | Access static members | Access non-static members |
| Variables | Doesn't use `$` because it's not a variable but representing the class construction | Uses `$` because it's a variable representing the instance |
| Class Members | Access class members using the scope resolution `::` | Access class members using the arrow `->` |
## Overriding

- Overriding is redefining a class member on the child class
- You can change the visability of the class member from private (in super) to public (in sub) but not the opposite
  - Tis happens because we have 3 levels of security
    - `public`: level 1 (least security)
    - `protected`: level 2
    - `private`: level 3 (most security)
  - You can change the visibility at the same level or lower only
- Constants can be overriden
- To prevent overriding, use `final` keyword in the super class before the visibility marker
- You can use `final` keyword with the classes to prevent extension (inheritence)

## Abstraction

- Abstract Class is a blueprint class that has no implementation or values for the member
- Abstract Class can be implemented, but the best practice is to do this through only inheritence
- Abstract Class can't be instantiated
- Abstract methods must be declared (can't use `abstract` with properties)
- Abstract methods have no body, but they can have arguments
- If abstract class/class members got deleted, nothing happen to the children. Just remove the `extends` keyword if the whole class got removed