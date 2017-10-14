<?php

namespace Pageon\Test\Html\Image;

use Pageon\Html\Image\Image;
use Stitcher\Test\StitcherTest;

class ImageTest extends StitcherTest
{
    /** @test */
    public function it_can_be_made()
    {
        $image = Image::make('resources/green.jpg');

        $this->assertInstanceOf(Image::class, $image);
    }
}
