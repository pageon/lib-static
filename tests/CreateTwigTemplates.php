<?php

namespace Stitcher\Test;

use Stitcher\File;
use Symfony\Component\Filesystem\Filesystem;

trait CreateTwigTemplates
{
    protected function createIndexTemplate(): void
    {
        $fs = new Filesystem();

        $fs->copy(__DIR__ . '/resources/twig/index.twig', File::path('template/index.twig'));
    }
}
