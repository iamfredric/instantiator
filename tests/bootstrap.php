<?php

class SimpleClass {}

interface NotInstantiable {}

class ConstructedWithDependencies
{
    public $simpleclass;

    public function __construct(SimpleClass $simpleclass)
    {
        $this->simpleclass = $simpleclass;
    }
}

class ConstructedWithNullParams
{
    public function __construct($value = null)
    {}
}

class HardToGuess
{
    function __construct($whatami)
    {}
}

class IHaveMethods
{

    public function __construct(SimpleClass $class)
    {}

    public function index()
    {
        return 'Instantiated';
    }

    public function show(ConstructedWithNullParams $class)
    {
        return $class;
    }

    protected function edit()
    {}
}

abstract class Modest {}

class User extends Modest {

    public static function make()
    {
        return 'user-made';
    }
}

class CanBeInstantiated implements NotInstantiable {}