<?php

namespace Stitcher\Page\Adapter;

use Stitcher\Adapter;
use Stitcher\Exception\InvalidConfiguration;
use Stitcher\Validatory;
use Stitcher\Variable\VariableParser;

class PaginationAdapter implements Adapter, Validatory
{
    private $variableParser;
    private $variable;
    private $perPage;

    public function __construct(array $adapterConfiguration, VariableParser $variableParser)
    {
        if (!$this->isValid($adapterConfiguration)) {
            throw InvalidConfiguration::invalidAdapterConfiguration('pagination', '`variable`, `perPage`');
        }

        $this->variable = $adapterConfiguration['variable'];
        $this->perPage = $adapterConfiguration['perPage'] ?? 12;
        $this->variableParser = $variableParser;
    }

    public static function make(array $adapterConfiguration, VariableParser $variableParser) : PaginationAdapter
    {
        return new self($adapterConfiguration, $variableParser);
    }

    public function transform(array $pageConfiguration) : array
    {
        $variable = $pageConfiguration['variables'][$this->variable] ?? null;
        $entries = $this->variableParser->parse($variable)['entries'] ?? [];
        $paginationPageConfiguration = [];

        $pageCount = (int) ceil(count($entries) / $this->perPage);
        $pageIndex = 1;

        while ($pageIndex <= $pageCount) {
            $entriesForPage = array_splice($entries, 0, $this->perPage);
            $entryConfiguration = $this->createEntryConfiguration($pageConfiguration, $entriesForPage, $pageIndex);

            $paginationPageConfiguration[$entryConfiguration['id']] = $entryConfiguration;
            $pageIndex += 1;
        }

        return $paginationPageConfiguration;
    }

    public function isValid($subject) : bool
    {
        return is_array($subject) && isset($subject['variable']);
    }

    private function createEntryConfiguration(array $entryConfiguration, array $entriesForPage, int $pageIndex) : array
    {
        $paginatedId = rtrim($entryConfiguration['id'], '/') . "/page-{$pageIndex}";
        $entryConfiguration['id'] = $paginatedId;
        $entryConfiguration['variables'][$this->variable] = $entriesForPage;
        unset($entryConfiguration['config']['pagination']);

        return $entryConfiguration;
    }
}
