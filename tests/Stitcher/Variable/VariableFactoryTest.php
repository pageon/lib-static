<?php

namespace Stitcher\Test\Stitcher\Variable;

use Brendt\Image\Config\DefaultConfigurator;
use Brendt\Image\ResponsiveFactory;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Stitcher\Variable\ImageVariable;
use Stitcher\Variable\JsonVariable;
use Stitcher\Variable\MarkdownVariable;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\YamlVariable;
use Symfony\Component\Yaml\Yaml;

class VariableFactoryTest extends StitcherTest
{
    /** @test */
    public function it_creates_the_right_variable()
    {
        $factory = new VariableFactory();
        $factory
            ->setYamlParser(new Yaml())
            ->setMarkdownParser(new \Parsedown())
            ->setImageParser($this->createResponsiveFactory());

        $this->assertInstanceOf(JsonVariable::class, $factory->create('test.json'));
        $this->assertInstanceOf(YamlVariable::class, $factory->create('test.yaml'));
        $this->assertInstanceOf(YamlVariable::class, $factory->create('test.yml'));
        $this->assertInstanceOf(MarkdownVariable::class, $factory->create('test.md'));
        $this->assertInstanceOf(ImageVariable::class, $factory->create('image.jpg'));
        $this->assertInstanceOf(ImageVariable::class, $factory->create('image.jpeg'));
        $this->assertInstanceOf(ImageVariable::class, $factory->create('image.png'));
        $this->assertInstanceOf(ImageVariable::class, $factory->create('image.gif'));
        $this->assertInstanceOf(ImageVariable::class, $factory->create([
            'src' => 'image.jpeg',
        ]));
    }

    private function createResponsiveFactory() : ResponsiveFactory
    {
        return new ResponsiveFactory(new DefaultConfigurator([
            'sourcePath' => File::path(),
            'publicPath' => File::path('/public'),
        ]));
    }
}
