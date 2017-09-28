<?php

namespace Stitcher\Page;

use Parsedown;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;

class PageParserTest extends StitcherTest
{
    /** @test */
    public function it_can_parse_a_page_config()
    {
        $parser = PageParser::make($this->createPageFactory());

        $page = $parser->parse([
            'id'       => '/',
            'template' => 'index.twig',
        ]);

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
        $page = $parser->parse([
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'title' => 'Test',
                'body'  => 'test.md',
            ],
        ]);

        $this->assertEquals('Test', $page->getVariable('title'));
        $this->assertEquals('<h1>Hello world</h1>', $page->getVariable('body'));
    }

    private function createPageFactory() : PageFactory
    {
        return PageFactory::make(
            VariableParser::make(
                VariableFactory::make()->setMarkdownParser(new Parsedown())
            )
        );
    }
}
