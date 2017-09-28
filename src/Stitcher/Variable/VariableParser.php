<?php

namespace Stitcher\Variable;

class VariableParser
{
    private $factory;

    public function __construct(VariableFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function create(VariableFactory $factory) : VariableParser
    {
        return new self($factory);
    }

    public function parse($value)
    {
        $variable = $this->factory->create($value);
        $parsed = $variable ? $variable->parse()->parsed() : $value;
        $parsed = $this->parseChildren($value, $parsed);

        return $parsed;
    }

    private function parseChildren($value, $parsed)
    {
        if (is_array($parsed)) {
            foreach ($parsed as &$parsedField) {
                $parsedField = $this->parse($parsedField);
            }
        } else {
            $childVariable = $this->factory->create($value);

            if ($childVariable) {
                $parsed = $this->parse($childVariable->parse()->parsed());
            }
        }

        return $parsed;
    }
}
