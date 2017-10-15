<?php

namespace Stitcher\Variable;

use Stitcher\File;
use Symfony\Component\Yaml\Yaml;

class YamlVariable extends AbstractVariable
{
    private $parser;

    public function __construct(string $unparsed, Yaml $parser, VariableParser $variableParser)
    {
        parent::__construct($unparsed);

        $this->parser = $parser;
    }

    public static function make(string $value, Yaml $parser, VariableParser $variableParser): YamlVariable
    {
        return new self($value, $parser, $variableParser);
    }

    public function parse(): AbstractVariable
    {
        $this->parsed = $this->parser->parse(File::read($this->unparsed));

        return $this;
    }
}
