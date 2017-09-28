<?php

namespace Stitcher\Variable;

use Stitcher\File;

class JsonVariable extends AbstractVariable
{
    public static function create(string $value) : JsonVariable
    {
        return new self($value);
    }

    public function parse() : AbstractVariable
    {
        $this->parsed = json_decode(File::get($this->value), true);

        return $this;
    }
}
