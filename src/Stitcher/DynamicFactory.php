<?php

namespace Stitcher;

abstract class DynamicFactory
{
    private $rules = [];

    abstract public function create($value);

    public function setRule(string $class, callable $callback) : DynamicFactory
    {
        $this->rules[$class] = $callback;

        return $this;
    }

    public function removeRule(string $class) : DynamicFactory
    {
        if (isset($this->rules[$class])) {
            unset($this->rules[$class]);
        }

        return $this;
    }

    protected function getRules() {
        return $this->rules;
    }
}
