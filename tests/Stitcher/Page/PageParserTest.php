<?php

namespace Stitcher\Page;

use Parsedown;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;
use Symfony\Component\Yaml\Yaml;

class PageParserTest extends StitcherTest
{
    /** @test */
    public function it_can_parse_a_page_config()
    {
        $parser = PageParser::make($this->createPageFactory());

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
        File::put($markdownPath, <<<EOT
# Hello world
EOT
        );

        $parser = PageParser::make($this->createPageFactory());
        $result = $parser->parse([
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'title' => 'Test',
                'body'  => 'test.md',
            ],
        ]);
        $page = reset($result);

        $this->assertEquals('Test', $page->getVariable('title'));
        $this->assertEquals('<h1>Hello world</h1>', $page->getVariable('body'));
    }

    /** @test */
    public function it_can_parse_a_collection_of_pages()
    {
        File::put('entries.yaml', <<<EOT
entries:
    a:
        name: A
    b:
        name: B
EOT
        );

        $parser = PageParser::make($this->createPageFactory());
        $result = $parser->parse([
            'id'        => '/%id%',
            'template'  => 'index.twig',
            'variables' => [
                'entry' => 'entries.yaml',
            ],
            'config' => [
                'collection' => [
                    'field' => 'entry',
                    'parameter' => 'id',
                ]
            ]
        ]);

        $this->assertTrue(is_array($result));
//        $this->assertArrayHasKey('/a', $result);
//        $this->assertArrayHasKey('/b', $result);
    }

    private function createPageFactory() : PageFactory
    {
        return PageFactory::make(
            VariableParser::make(
                VariableFactory::make()
                    ->setMarkdownParser(new Parsedown())
                    ->setYamlParser(new Yaml())
            )
        );
    }
}
