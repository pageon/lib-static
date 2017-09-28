<?php

namespace Stitcher\Test\Stitcher\Variable;

use Brendt\Image\Config\DefaultConfigurator;
use Brendt\Image\ResponsiveFactory;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\ImageVariable;

class ImageVariableTest extends StitcherTest
{
    /** @test */
    public function it_can_be_parsed()
    {
        $path = File::path('/image_test.jpg');
        File::put($path, @file_get_contents($this->getTestDir() . '/resources/green.jpg'));

        $variable = ImageVariable::create('image_test.jpg', $this->createResponsiveFactory())->parse();

        $parsed = $variable->parsed();
        $this->assertTrue(is_array($parsed));
        $this->assertArrayHasKey('src', $parsed, '`src` not found in parsed image.');
        $this->assertArrayHasKey('srcset', $parsed, '`srcset not found in parsed image.`');
        $this->assertEquals('/image_test.jpg', $parsed['src'], '`src` does not match expected value in parsed image.');
        $this->assertContains('/image_test', $parsed['srcset'], '`srcset` does not match expected value in parsed image.');
    }

    /** @test */
    public function it_can_be_parsed_with_alt()
    {
        $path = File::path('/image_test.jpg');
        File::put($path, @file_get_contents($this->getTestDir() . '/resources/green.jpg'));

        $variable = ImageVariable::create([
            'src' => 'image_test.jpg',
            'alt' => 'test',
        ], $this->createResponsiveFactory())->parse();

        $parsed = $variable->parsed();
        $this->assertArrayHasKey('alt', $parsed, '`alt not found in parsed image.`');
        $this->assertEquals('test', $parsed['alt'], '`alt` does not match expected value in parsed image.');
        $this->assertEquals('/image_test.jpg', $parsed['src'], '`src` does not match expected value in parsed image.');
    }

    private function createResponsiveFactory() : ResponsiveFactory
    {
        return new ResponsiveFactory(new DefaultConfigurator([
            'sourcePath' => File::path(),
            'publicPath' => File::path('/public'),
        ]));
    }
}
