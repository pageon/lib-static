<?php

namespace Stitcher\Page;

use Stitcher\Page\Adapter\AdapterFactory;

class PageParser
{
    private $pageFactory;
    private $adapterFactory;

    public function __construct(PageFactory $pageFactory, AdapterFactory $adapterFactory)
    {
        $this->pageFactory = $pageFactory;
        $this->adapterFactory = $adapterFactory;
    }

    public static function make(PageFactory $factory, AdapterFactory $adapterFactory) : PageParser
    {
        return new self($factory, $adapterFactory);
    }

    /**
     * @param $inputConfiguration
     *
     * @return Page[]
     */
    public function parse($inputConfiguration) : array
    {
        $result = [];

        $adaptedInputConfiguration = $this->parseAdapterConfiguration($inputConfiguration);
        foreach ($adaptedInputConfiguration as $adaptedPageConfiguration) {
            $page = $this->parsePage($adaptedPageConfiguration);

            $result[$page->getId()] = $page;
        }

        return $result;
    }

    private function parseAdapterConfiguration(array $pageConfiguration) : array
    {
        $adaptedPageConfiguration = $pageConfiguration;
        $adapterInputConfiguration = $pageConfiguration['config'] ?? $pageConfiguration['adapters'] ?? [];

        foreach ($adapterInputConfiguration as $adapterType => $adapterConfiguration) {
            $adapter = $this->adapterFactory->create($adapterType, $adapterConfiguration);
            $adaptedPageConfiguration = $adapter->transform($adaptedPageConfiguration);
        }

        return $adaptedPageConfiguration;
    }

    private function parsePage($inputConfiguration) : Page
    {
        return $this->pageFactory->create($inputConfiguration);
    }
}
