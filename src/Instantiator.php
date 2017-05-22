<?php

namespace Iamfredric\Instantiator;

use Iamfredric\Instantiator\Exceptions\InstantiationException;
use ReflectionClass;
use ReflectionMethod;

class Instantiator
{
    /**
     * @var ReflectionClass
     */
    protected $reflection;

    /**
     * @var string
     */
    protected $classname;

    /**
     * Instantiator constructor.
     *
     * @param $classname
     */
    public function __construct($classname)
    {
        $this->reflection = new ReflectionClass($classname);
        $this->classname = $classname;
    }

    /**
     * News up the class object and resolves its dependencies
     *
     * @return object
     *
     * @throws InstantiationException
     */
    public function call()
    {
        if (! $this->reflection->isInstantiable()) {
            throw new InstantiationException("Class {$this->classname} cannot be called directly");
        }

        $constructor = $this->reflection->getConstructor();

        if (is_null($constructor)) {
            return new $this->classname;
        }

        return $this->reflection->newInstanceArgs(
            $this->getDependencies($constructor->getParameters())
        );
    }

    /**
     * News up the class object and calls given method
     * and resolves class and method dependencies
     *
     * @param $methodName
     *
     * @return mixed
     *
     * @throws InstantiationException
     */
    public function callMethod($methodName)
    {
        if (! $this->reflection->hasMethod($methodName)) {
            throw new InstantiationException("Method {$methodName} does not exist");
        }

        $classMethod = new ReflectionMethod($this->classname, $methodName);

        $method = $this->reflection->getMethod($methodName);

        if (! $method->isPublic()) {
            throw new InstantiationException("Method {$methodName} must be public");
        }

        return $classMethod->invokeArgs(
            $this->call(),
            $this->getDependencies($method->getParameters())
        );
    }

    /**
     * Resolves given param dependencies
     *
     * @param $params
     *
     * @return array
     */
    protected function getDependencies($params)
    {
        $dependencies = [];

        foreach ($params as $param) {
            $dependency = $param->getClass();

            if (is_null($dependency)) {
                $dependencies[] = $this->resolveNullClass($param);
            } else {
                $dependencies[] = (new self($dependency->name))->call();
            }
        }

        return $dependencies;
    }

    /**
     * Resolves null param
     *
     * @param $parameter
     *
     * @return mixed
     *
     * @throws InstantiationException
     */
    public function resolveNullClass($parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new InstantiationException("Cannot resolve dependency {$parameter}");
    }
}
