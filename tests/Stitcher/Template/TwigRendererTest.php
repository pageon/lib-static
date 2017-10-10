<?php

namespace Stitcher\Renderer;

use Stitcher\File;
use Stitcher\Test\StitcherTest;

class TwigRendererTest extends StitcherTest
{
    /** @test */
    public function it_can_render_a_template()
    {
        $renderer = TwigRenderer::make(File::path('template'));
        $path = File::path('template/index.twig');
        File::put($path, <<<EOT
{{ variable }}
EOT
        );

        $html = $renderer->renderTemplate('index.twig', [
            'variable' => 'hello world'
        ]);

        $this->assertEquals('hello world', $html);
    }
}
