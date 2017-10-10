<?php

namespace Stitcher\Page\Adapter;

use Stitcher\Adapter;
use Stitcher\Exception\InvalidConfiguration;
use Stitcher\Validatory;
use Stitcher\Variable\VariableParser;

class CollectionAdapter implements Adapter, Validatory
{
    private $parameter;
    private $variable;
    private $variableParser;

    public function __construct(array $adapterConfiguration, VariableParser $variableParser)
    {
        if (!$this->isValid($adapterConfiguration)) {
            throw InvalidConfiguration::invalidAdapterConfiguration('collection', '`variable` and `parameter`');
        }

        $this->variable = $adapterConfiguration['variable'];
        $this->parameter = $adapterConfiguration['parameter'];
        $this->variableParser = $variableParser;
    }

    public static function make(array $adapterConfiguration, VariableParser $variableParser)
    {
        return new self($adapterConfiguration, $variableParser);
    }

    public function transform(array $pageConfiguration) : array
    {
        $variable = $pageConfiguration['variables'][$this->variable] ?? null;
        $entries = $this->variableParser->parse($variable)['entries'] ?? [];
        $collectionPageConfiguration = [];

        foreach ($entries as $entryId => $entry) {
            $entryConfiguration = $pageConfiguration;
            $parsedEntryId = str_replace('{' . $this->parameter . '}', $entryId, $pageConfiguration['id']);
            $entryConfiguration['id'] = $parsedEntryId;
            $entryConfiguration['variables'][$this->variable] = $entry;
            unset($entryConfiguration['config']['collection']);

            $collectionPageConfiguration[$parsedEntryId] = $entryConfiguration;
        }

        return $collectionPageConfiguration;
    }

    public function isValid($subject) : bool
    {
        return is_array($subject) && isset($subject['variable']) && isset($subject['parameter']);
    }
}
