<?php

namespace Stitcher\Page\Adapter;

use Parsedown;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;
use Symfony\Component\Yaml\Yaml;

class CollectionAdapterTest extends StitcherTest
{
    /** @test */
    public function it_can_transform_a_single_collection_into_multiple()
    {
        File::put('entries.yaml', <<<EOT
entries:
    a:
        name: A
    b:
        name: B
EOT
        );

        $pageConfiguration = [
            'id'        => '/{id}',
            'template'  => 'index.twig',
            'variables' => [
                'entry' => 'entries.yaml',
            ],
            'config'    => [
                'collection' => [
                    'variable'  => 'entry',
                    'parameter' => 'id',
                ],
            ],
        ];

        $adapter = CollectionAdapter::make($pageConfiguration['config']['collection'], $this->createVariableParser());
        $result = $adapter->transform($pageConfiguration);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('/a', $result);
        $this->assertEquals('A', $result['/a']['variables']['entry']['name']);
        $this->assertArrayHasKey('/b', $result);
        $this->assertEquals('B', $result['/b']['variables']['entry']['name']);
    }

    private function createVariableParser() : VariableParser
    {
        return VariableParser::make(
            VariableFactory::make()
                ->setYamlParser(new Yaml())
        );
    }
}
