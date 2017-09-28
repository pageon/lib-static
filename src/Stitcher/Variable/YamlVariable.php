<?php

namespace Stitcher\Variable;

use Stitcher\File;
use Symfony\Component\Yaml\Yaml;

class YamlVariable extends AbstractVariable
{
    private $parser;

    public function __construct(string $value, Yaml $parser)
    {
        parent::__construct($value);

        $this->parser = $parser;
    }

    public static function make(string $value, Yaml $parser) : YamlVariable
    {
        return new self($value, $parser);
    }

    public function parse() : AbstractVariable
    {
        $this->parsed = $this->parser->parse(File::get($this->value));

        return $this;
    }
}
