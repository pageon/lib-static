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
        if (! $this->isValid($adapterConfiguration)) {
            throw InvalidConfiguration::invalidAdapterConfiguration('pagination', '`variable`, `perPage`');
        }

        $this->variable = $adapterConfiguration['variable'];
        $this->perPage = $adapterConfiguration['perPage'] ?? 12;
        $this->variableParser = $variableParser;
    }

    public static function make(array $adapterConfiguration, VariableParser $variableParser): PaginationAdapter
    {
        return new self($adapterConfiguration, $variableParser);
    }

    public function transform(array $pageConfiguration): array
    {
        $variable = $pageConfiguration['variables'][$this->variable] ?? null;
        $entries = $this->variableParser->parse($variable)['entries'] ?? $variable;
        $paginationPageConfiguration = [];

        $pageCount = (int) ceil(count($entries) / $this->perPage);
        $pageIndex = 1;

        while ($pageIndex <= $pageCount) {
            $entriesForPage = array_splice($entries, 0, $this->perPage);

            $entryConfiguration = $this->createEntryConfiguration(
                $pageConfiguration,
                $entriesForPage,
                $pageIndex,
                $pageCount
            );

            $paginationPageConfiguration[$entryConfiguration['id']] = $entryConfiguration;

            $pageIndex += 1;
        }

        return $paginationPageConfiguration;
    }

    public function isValid($subject): bool
    {
        return is_array($subject) && isset($subject['variable']);
    }

    private function createEntryConfiguration(
        array $entryConfiguration,
        array $entriesForPage,
        int $pageIndex,
        int $pageCount
    ): array {
        $pageId = rtrim($entryConfiguration['id'], '/');
        $paginatedId = "{$pageId}/page-{$pageIndex}";

        $entryConfiguration['id'] = $paginatedId;
        $entryConfiguration['variables'][$this->variable] = $entriesForPage;

        $paginationVariable = $this->createPaginationVariable($pageId, $pageIndex, $pageCount);
        $entryConfiguration['variables']['pagination'] = $paginationVariable;

        unset($entryConfiguration['config']['pagination']);

        return $entryConfiguration;
    }

    private function createPaginationVariable(string $pageId, int $pageIndex, int $pageCount): array
    {
        $next =  $pageIndex < $pageCount ? $pageIndex + 1 : null;
        $nextUrl = $next ? "{$pageId}/page-{$next}" : null;

        $previous = $pageIndex > 1 ? $pageIndex - 1 : null;
        $previousUrl = $previous ? "{$pageId}/page-{$previous}" : null;

        return [
            'current'  => $pageIndex,
            'previous' => $previous ? [
                'url'   => $previousUrl,
                'index' => $previous,
            ] : null,
            'next'     => $next ? [
                'url'   => $nextUrl,
                'index' => $next,
            ] : null,
            'pages'    => $pageCount,
        ];
    }
}
