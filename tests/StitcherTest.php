<?php

namespace Stitcher\Test;

use PHPUnit\Framework\TestCase;
use Stitcher\File;
use Symfony\Component\Filesystem\Filesystem;

abstract class StitcherTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        File::base(__DIR__ . '/../data');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $fs = new Filesystem();
        $dataDir = File::path();

        if ($fs->exists($dataDir)) {
            $fs->remove($dataDir);
        }

        File::base(null);
    }
}
