<?php

namespace Stitcher\Variable;

class VariableParser
{
    private $factory;

    public function __construct(VariableFactory $factory)
    {
        $this->factory = $factory;
        $this->factory->setVariableParser($this);
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

    public function getVariable($unparsedValue): AbstractVariable
    {
        return $this->factory->create($unparsedValue);
    }
}
