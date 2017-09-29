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

    public function parse($inputConfiguration) : array
    {
        $result = [];
        $pageConfiguration = $inputConfiguration['config'] ?? $inputConfiguration['adapters'] ?? [];

        foreach ($pageConfiguration as $adapterType => $adapterConfiguration) {

        }

        $result[] = $this->parsePage($inputConfiguration);

        return $result;
    }

    private function parsePage($inputConfiguration) : Page
    {
        return $this->factory->create($inputConfiguration);
    }
}
