<?php

namespace Stitcher\Page;

use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\CreateTwigTemplates;
use Stitcher\Test\StitcherTest;

class PageRendererTest extends StitcherTest
{
    use CreateStitcherObjects;
    use CreateTwigTemplates;

    /** @test */
    public function it_can_render_a_page_as_html()
    {
        $this->createIndexTemplate();

        $variableParser = $this->createVariableParser();
        $parser = $this->createPageParser($variableParser);
        $result = $parser->parse([
            'id'        => '/',
            'template'  => 'index.twig',
            'variables' => [
                'variable' => 'Hello world',
            ],
        ]);

        $renderer = $this->createPageRenderer();
        $html = $renderer->render($result->first());

        $this->assertContains('Hello world', $html);
    }
}
