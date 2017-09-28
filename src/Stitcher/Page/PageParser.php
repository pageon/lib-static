<?php

namespace Stitcher\Page;

class PageParser
{
    private $factory;

    public function __construct(PageFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function make(PageFactory $factory) : PageParser
    {
        return new self($factory);
    }

    public function parse($value) : Page
    {
        $page = $this->factory->create($value);

        return $page;
    }
}
