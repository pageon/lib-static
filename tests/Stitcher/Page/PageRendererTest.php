<?php

namespace Stitcher\Page;

use Stitcher\File;
use Stitcher\Page\Adapter\AdapterFactory;
use Stitcher\Template\TwigRenderer;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;

class PageRendererTest extends StitcherTest
{
    /** @test */
    public function it_can_render_a_page_as_html()
    {
        $path = File::path('template/index.twig');
        File::put($path, <<<EOT
{{ variable }}
EOT
        );

        $variableParser = $this->createVariableParser();
        $parser = $this->createPageParser($variableParser);
        $result = $parser->parse([
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'variable' => 'Hello world',
            ],
        ]);
        $page = reset($result);

        $renderer = $this->createPageRenderer();
        $html = $renderer->render($page);

        $this->assertEquals('Hello world', $html);
    }

    private function createVariableParser() : VariableParser
    {
        return VariableParser::make(
            VariableFactory::make()
        );
    }

    private function createPageParser(VariableParser $variableParser) : PageParser
    {
        return PageParser::make(
            PageFactory::make($variableParser),
            AdapterFactory::make($variableParser)
        );
    }

    private function createPageRenderer() : PageRenderer
    {
        return PageRenderer::make(
            TwigRenderer::make(File::path('/template'))
        );
    }
}
