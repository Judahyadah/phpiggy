<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerException;

class Container
{
    private array $definitions = [];
    private array $resolved = [];


    public function addDefinitions(array $newDefinitions)
    {
        // $this->definitions = array_merge($this->definitions, $newDefinitions);
        // the result of the above function and the object below is just the joining of two arrays into one so definitions and new definitions are joined into one and the spread operator (...) below is used to unpack an array to let us be able to put in in a new array
        $this->definitions = [...$this->definitions, ...$newDefinitions];

    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);

        if(!$reflectionClass->isInstantiable()){
            throw new ContainerException("Class {$className} is not instantiable");
        }
        $constructor = $reflectionClass->getConstructor();
        if(!$constructor){
            return new $className;
        }

        $params = $constructor->getParameters();
        if(count($params) === 0){
            return new $className;
        }

        $dependencies = [];

        foreach($params as $param){
            $name = $param->getName();
            $type = $param->getType();

            if(!$type) {
                throw new ContainerException("Failed to resolve class {$className} because param {$name} is missing a type hint.");
            }
            if(!$type instanceof ReflectionNamedType || $type->isBuiltin()){
                throw new ContainerException("Failed to resolve class {$className} because invalid param name.");
            }
            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {
        if(!array_key_exists($id, $this->definitions)){
            throw new ContainerException("Class {$id} does not exist in container");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factory = $this->definitions[$id];
        $dependency = $factory($this);

        $this->resolved[$id] = $dependency;

        return $dependency;
    }
}