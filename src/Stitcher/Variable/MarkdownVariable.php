<?php

namespace Stitcher\Variable;

use Parsedown;
use Stitcher\Exception\InvalidConfiguration;
use Stitcher\File;

class MarkdownVariable extends AbstractVariable
{
    private $parser;

    public function __construct(string $value, Parsedown $parser)
    {
        parent::__construct($value);

        $this->parser = $parser;
    }

    public static function make(string $value, Parsedown $parser) : MarkdownVariable
    {
        return new self($value, $parser);
    }

    public function parse() : AbstractVariable
    {
        $contents = File::read($this->value);

        if (!$contents) {
            throw InvalidConfiguration::fileNotFound($this->value);
        }

        $this->parsed = $this->parser->parse($contents);

        return $this;
    }
}
