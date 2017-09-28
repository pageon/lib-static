<?php

namespace Stitcher\Variable;

use Stitcher\Parseable;

abstract class AbstractVariable implements Parseable
{
    protected $value;
    protected $parsed = null;

    public abstract function parse() : AbstractVariable;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    public function parsed()
    {
        if (!$this->parsed) {
            $this->parse();
        }

        return $this->parsed;
    }
}
