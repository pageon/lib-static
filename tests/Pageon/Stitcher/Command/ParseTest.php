<?php

namespace Pageon\Test\Stitcher\Command;

use Pageon\Stitcher\Command\Parse;
use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\CreateTwigTemplates;
use Stitcher\Test\StitcherTest;

class ParseTest extends StitcherTest
{
    use CreateStitcherObjects;
    use CreateTwigTemplates;

    /** @test */
    public function test_parse()
    {
        $this->createIndexTemplate();
        $this->createConfigurationFile();

        $command = Parse::make(
            File::path('public'),
            File::path('site.yaml'),
            $this->createPageParser(),
            $this->createPageRenderer()
        );

        $command->execute();

        $this->assertFileExists(File::path('public/index.html'));
        $this->assertFileExists(File::path('public/test.html'));
    }

    private function createConfigurationFile()
    {
        File::write('site.yaml', <<<EOT
/:
    template: index.twig
/test:
    template: index.twig
EOT
        );
    }
}
