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

    /** @test */
    function you_can_specify_bindings()
    {
        \Iamfredric\Instantiator\InstantiationsBinder::bind('Loremdolor', IHaveMethods::class);
        $instantiator = new \Iamfredric\Instantiator\Instantiator('Loremdolor');

        $this->assertInstanceOf(IHaveMethods::class, $instantiator->call());
    }

    /** @test */
    function you_can_specify_bindings_as_callbacks()
    {
        \Iamfredric\Instantiator\InstantiationsBinder::bind('Model', function () {
            return 'Model-resolved';
        });

        $instantiator = new \Iamfredric\Instantiator\Instantiator('Model');

        $this->assertEquals('Model-resolved', $instantiator->call());
    }

    /** @test */
    function you_can_specify_parents_bindings()
    {
        \Iamfredric\Instantiator\InstantiationsBinder::bind('Modest', function ($class) {
            return $class::make();
        });

        $instantiator = new \Iamfredric\Instantiator\Instantiator(User::class);

        $this->assertEquals('user-made', $instantiator->call());
    }

    /** @test */
    function you_can_bind_an_interface_to_a_class()
    {
        \Iamfredric\Instantiator\InstantiationsBinder::bind(NotInstantiable::class, CanBeInstantiated::class);

        $instantiator = new \Iamfredric\Instantiator\Instantiator(NotInstantiable::class);

        $this->assertInstanceOf(CanBeInstantiated::class, $instantiator->call());
    }
}