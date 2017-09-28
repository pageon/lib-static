<?php

namespace Stitcher\Variable;

use Parsedown;

class MarkdownVariable extends AbstractVariable
{
    private $parser;

    public function __construct(string $value, Parsedown $parser)
    {
        parent::__construct($value);

        $this->parser = $parser;
    }

    public static function create(string $value, Parsedown $parser) : MarkdownVariable
    {
        return new self($value, $parser);
    }

    public function parse() : AbstractVariable
    {
        $this->parsed = $this->parser->parse(@file_get_contents($this->value));

        return $this;
    }
}
