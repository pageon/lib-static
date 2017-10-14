<?php

namespace Stitcher\Page;

use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\StitcherTest;

class PageRendererTest extends StitcherTest
{
    use CreateStitcherObjects;

    /** @test */
    public function it_can_render_a_page_as_html()
    {
        $path = File::path('template/index.twig');
        File::write($path, <<<EOT
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
}
