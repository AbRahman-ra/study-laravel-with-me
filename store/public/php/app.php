<?php

namespace C;

// include "./Person.php";
// include "./../php2/Person.php";

use A\Person;
use B\Person as PersonB;

$person = new Person();
$person2 = new PersonB();

echo "<pre>";
print_r($person);

// Accessing a constant
echo Person::MALE;
echo "<br>";
echo $person::MALE;
echo "<br>";

// Accessing a static property
echo Person::$eyeColor;
echo "<br>";
echo $person::$eyeColor;
echo "<br>";

// Accessing a static methods
echo Person::getNumberOfPpl();
echo "<br>";
echo $person::getNumberOfPpl();
echo "<br>";
echo "</pre>";

echo "-----------------------------------------------------<br><br><hr><br><br>";

$person2 = new \B\Person;
print_r($person2->changePassword(1234));

$person2::$eyeColor = "blue";

echo "<pre>";
echo $person::$eyeColor; // It's blue not brown because it's a static property, whoever changed it it will be changed for all instances (class based)
echo "<br>";
echo "</pre>";

