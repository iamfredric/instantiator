<?php

class MethodResolutionTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    function it_resolves_a_class_method()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('IHaveMethods');

        $this->assertEquals('Instantiated', $instantiator->callMethod('index'));
    }

    /** @test */
    function it_resolves_a_class_method_with_dependencies()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('IHaveMethods');

        $this->assertInstanceOf(ConstructedWithNullParams::class, $instantiator->callMethod('show'));
    }

    /** @test */
    function it_cannot_call_non_public_methods()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('IHaveMethods');

        $this->expectException(\Iamfredric\Instantiator\Exceptions\InstantiationException::class);
        $instantiator->callMethod('edit');
    }

    /** @test */
    function it_cannot_call_an_undefined_method()
    {
        $instantiator = new \Iamfredric\Instantiator\Instantiator('IHaveMethods');

        $this->expectException(\Iamfredric\Instantiator\Exceptions\InstantiationException::class);
        $instantiator->callMethod('waevva');
    }
}