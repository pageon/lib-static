<?php

namespace Pageon\Html\Image;

use Stitcher\File;
use Stitcher\Test\StitcherTest;

class ImageTest extends StitcherTest
{
    /** @test */
    public function it_can_be_made()
    {
        $image = Image::make('resources/green.jpg');

        $this->assertInstanceOf(Image::class, $image);
    }

    /** @test */
    public function it_creates_multiple_variations_of_one_source()
    {
        $public = File::path('public');

        Image::make('resources/green_large.jpg')
            ->withScaler(FixedWidthScaler::make([
                300, 500
            ]))
            ->loadFrom(__DIR__ . '/../../../')
            ->saveIn($public);

        $this->assertNotNull(File::read('public/resources/green_large.jpg'));
        $this->assertNotNull(File::read('public/resources/green_large-500x500.jpg'));
        $this->assertNotNull(File::read('public/resources/green_large-300x300.jpg'));
    }

    /** @test */
    public function it_adds_the_srcset()
    {
        $public = File::path('public');

        $image = Image::make('resources/green_large.jpg')
            ->withScaler(FixedWidthScaler::make([
                300, 500
            ]))
            ->loadFrom(__DIR__ . '/../../../')
            ->saveIn($public);

        $srcset = $image->srcset();

        $this->assertContains('/resources/green_large.jpg 2500w', $srcset);
        $this->assertContains('/resources/green_large-500x500.jpg 500w', $srcset);
        $this->assertContains('/resources/green_large-300x300.jpg 300w', $srcset);
    }
}
