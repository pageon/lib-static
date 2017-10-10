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

    /**
     * @param $value
     *
     * @return mixed
     */
    public function parse($value)
    {
        $variable = $this->factory->create($value);
        $parsed = $variable ? $variable->parsed() : $value;
        $parsed = $this->parseChildren($value, $parsed);

        return $parsed;
    }

    /**
     * @param $value
     * @param $parsed
     *
     * @return mixed
     */
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
