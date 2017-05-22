# Instantiator
This is a small library for automaticly resolving classes. 

## How to use it
```php
<?php
use Iamfredric\Instantiator\Instantiator;

// Instantiate the instantiator
$instantiator = new Instantiator('MyClass');

// Resolve a class
$myClass = $instantiator->call();

// Resolve a class method
$output = $instantiator->callMethod('myMethod');
```