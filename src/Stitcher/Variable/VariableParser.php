<?php

namespace Stitcher\Variable;

class VariableParser
{
    private $factory;

    public function __construct(VariableFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function make(VariableFactory $factory): VariableParser
    {
        return new self($factory);
    }

    public function parse($unparsedValue)
    {
        $parsedValue = null;
        $variable = $this->factory->create($unparsedValue);

        if ($variable) {
            $parsedValue = $variable->parsed();
        } else {
            $parsedValue = $unparsedValue;
        }

        return $parsedValue;
    }
}
