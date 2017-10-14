<?php

namespace Stitcher\Page\Adapter;

use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\StitcherTest;

class PaginationAdapterTest extends StitcherTest
{
    use CreateStitcherObjects;

    /** @test */
    public function it_can_transform_a_collection_of_entries_into_multiple_pages()
    {
        File::write('entries.yaml', <<<EOT
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
                'pagination' => [
                    'variable' => 'entries',
                    'perPage'  => 2,
                ],
            ],
        ];

        $adapter = PaginationAdapter::make($pageConfiguration['config']['pagination'], $this->createVariableParser());
        $result = $adapter->transform($pageConfiguration);

        $this->assertCount(2, $result);

        $this->assertCount(2, $result['/page-1']['variables']['entries']);
        $this->assertEquals('A', $result['/page-1']['variables']['entries']['a']['name']);
        $this->assertEquals('B', $result['/page-1']['variables']['entries']['b']['name']);

        $this->assertCount(1, $result['/page-2']['variables']['entries']);
        $this->assertEquals('C', $result['/page-2']['variables']['entries']['c']['name']);
    }
}
