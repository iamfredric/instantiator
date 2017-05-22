<?php

class ClassresolutionTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    function a_class_without_params_can_be_resolved()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('SimpleClass');

        $this->assertInstanceOf(SimpleClass::class, $instantiator->call());
    }

    /** @test */
    function an_interface_cannot_be_called()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('NotInstantiable');
        $this->expectException(\Iamfredric\Instantiator\Exceptions\InstantiationException::class);
        $instantiator->call();
    }

    /** @test */
    function it_resolves_constructor_dependencies_and_injects_them()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('ConstructedWithDependencies');

        $class = $instantiator->call();

        $this->assertInstanceOf(ConstructedWithDependencies::class, $class);
        $this->assertInstanceOf(SimpleClass::class, $class->simpleclass);
    }

    /** @test */
    function it_ignores_null_on_dependencies()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('ConstructedWithNullParams');

        $this->assertInstanceOf(ConstructedWithNullParams::class, $instantiator->call());
    }

    /** @test */
    function it_tells_you_when_it_cannot_resolve_dependency()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('HardToGuess');

        $this->expectException(\Iamfredric\Instantiator\Exceptions\InstantiationException::class);
        $instantiator->call();
    }
}