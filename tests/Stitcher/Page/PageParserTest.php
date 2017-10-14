<?php

namespace Stitcher\Page;

use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\StitcherTest;

class PageParserTest extends StitcherTest
{
    use CreateStitcherObjects;

    /** @test */
    public function it_can_parse_a_page_config()
    {
        $variableParser = $this->createVariableParser();
        $parser = PageParser::make($this->createPageFactory($variableParser), $this->createAdapterFactory($variableParser));

        $result = $parser->parse([
            'id'       => '/',
            'template' => 'index.twig',
        ]);
        $page = reset($result);

        $this->assertTrue(is_array($result));
        $this->assertInstanceOf(Page::class, $page);
    }

    /** @test */
    public function it_can_parse_variables()
    {
        $markdownPath = File::path('test.md');
        File::write($markdownPath, <<<EOT
# Hello world
EOT
        );

        $variableParser = $this->createVariableParser();
        $parser = PageParser::make($this->createPageFactory($variableParser), $this->createAdapterFactory($variableParser));
        $result = $parser->parse([
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'title' => 'Test',
                'body'  => 'test.md',
            ],
        ]);
        $page = reset($result);

        $this->assertEquals('Test', $page->variable('title'));
        $this->assertEquals('<h1>Hello world</h1>', $page->variable('body'));
    }

    /** @test */
    public function it_can_parse_a_collection_of_pages()
    {
        File::write('entries.yaml', <<<EOT
entries:
    a:
        name: A
    b:
        name: B
EOT
        );

        $variableParser = $this->createVariableParser();
        $parser = PageParser::make(
            $this->createPageFactory($variableParser),
            $this->createAdapterFactory($variableParser)
        );

        $result = $parser->parse([
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
        ]);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('/a', $result);
        $this->assertArrayHasKey('/b', $result);

        $pageA = $result['/a'];
        $this->assertEquals('A', $pageA->variable('entry')['name']);

        $pageB = $result['/b'];
        $this->assertEquals('B', $pageB->variable('entry')['name']);
    }
}
