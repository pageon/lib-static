<?php

namespace Stitcher\Renderer;

use Stitcher\File;
use Stitcher\Test\CreateTwigTemplates;
use Stitcher\Test\StitcherTest;

class TwigRendererTest extends StitcherTest
{
    use CreateTwigTemplates;

    /** @test */
    public function it_can_render_a_template()
    {
        $renderer = TwigRenderer::make(File::path('template'));
        $this->createIndexTemplate();

        $html = $renderer->renderTemplate('index.twig', [
            'variable' => 'hello world'
        ]);

        $this->assertContains('hello world', $html);
    }
}
