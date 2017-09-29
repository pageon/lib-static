<?php

namespace Stitcher\Page\Adapter;

use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;
use Symfony\Component\Yaml\Yaml;

class FilterAdapterTest extends StitcherTest
{
    /** @test */
    public function it_can_filter_values()
    {
        File::put('entries.yaml', <<<EOT
entries:
    a:
        name: A
        category: blog
    b:
        name: B
        category: blog
    c:
        name: C
        category: news
EOT
        );

        $pageConfiguration = [
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'entries' => 'entries.yaml',
            ],
            'config'    => [
                'filter' => [
                    'entries' => [
                        'category' => 'blog',
                        'name'     => 'A',
                    ],
                ],
            ],
        ];

        $adapter = FilterAdapter::make($pageConfiguration['config']['filter'], $this->createVariableParser());
        $result = $adapter->transform($pageConfiguration);
        $entries = $result['variables']['entries'];

        $this->assertArrayHasKey('a', $entries);
        $this->assertArrayNotHasKey('b', $entries);
        $this->assertArrayNotHasKey('c', $entries);
    }

    private function createVariableParser() : VariableParser
    {
        return VariableParser::make(
            VariableFactory::make()
                ->setYamlParser(new Yaml())
        );
    }
}