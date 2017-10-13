<?php

namespace Pageon\Html\Image;

use Stitcher\File;
use Stitcher\Test\StitcherTest;

class ImageFactoryTest extends StitcherTest
{
    /** @test */
    public function it_creates_multiple_variations_of_one_source()
    {
        $public = File::path('public');

        $factory = ImageFactory::make(__DIR__ . '/../../../', $public, FixedWidthScaler::make([
            300, 500,
        ]));

        $factory->create('resources/green_large.jpg');

        $this->assertNotNull(File::read('public/resources/green_large.jpg'));
        $this->assertNotNull(File::read('public/resources/green_large-500x500.jpg'));
        $this->assertNotNull(File::read('public/resources/green_large-300x300.jpg'));
    }

    /** @test */
    public function it_adds_the_srcset()
    {
        $public = File::path('public');

        $factory = ImageFactory::make(__DIR__ . '/../../../', $public, FixedWidthScaler::make([
            300, 500,
        ]));

        $image = $factory->create('resources/green_large.jpg');
        $srcset = $image->srcset();

        $this->assertContains('/resources/green_large.jpg 2500w', $srcset);
        $this->assertContains('/resources/green_large-500x500.jpg 500w', $srcset);
        $this->assertContains('/resources/green_large-300x300.jpg 300w', $srcset);
    }
}
