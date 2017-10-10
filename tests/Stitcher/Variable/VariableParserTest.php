<?php

namespace Stitcher\Variable;

use Brendt\Image\Config\DefaultConfigurator;
use Brendt\Image\ResponsiveFactory;
use Parsedown;
use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Symfony\Component\Yaml\Yaml;

class VariableParserTest extends StitcherTest
{
    /** @test */
    public function it_can_parse_a_single_variable()
    {
        $path = File::path('/YamlVariableTest_test_recursive_parent.yaml');
        File::write($path, <<<EOT
entry:
    title: Test
EOT
        );

        $variableParser = VariableParser::make($this->createVariableFactory());
        $parsed = $variableParser->parse($path);

        $this->assertTrue(is_array($parsed));
        $this->assertTrue(isset($parsed['entry']['title']));
    }

    /** @test */
    public function it_can_be_parsed_recursively()
    {
        $path = File::path('YamlVariableTest_test_recursive_parent.yaml');
        $this->createRecursiveFiles($path);

        $variableParser = VariableParser::make($this->createVariableFactory());
        $parsed = $variableParser->parse($path);

        $this->assertTrue(isset($parsed['entry']['child']['title']));
    }

    private function createVariableFactory() : VariableFactory
    {
        $factory = VariableFactory::make()
            ->setMarkdownParser(new Parsedown())
            ->setYamlParser(new Yaml())
            ->setImageParser($this->createResponsiveFactory());

        return $factory;
    }

    private function createResponsiveFactory() : ResponsiveFactory
    {
        return new ResponsiveFactory(new DefaultConfigurator([
            'sourcePath' => File::path(),
            'publicPath' => File::path('/public'),
        ]));
    }

    private function createRecursiveFiles(string $path)
    {
        $parentPath = File::path($path);
        File::write($parentPath, <<<EOT
entry:
    title: Test
    child: YamlVariableTest_test_recursive_child.yaml
    body: body.md
EOT
        );

        $childPath = File::path('YamlVariableTest_test_recursive_child.yaml');
        File::write($childPath, <<<EOT
title: Child
EOT
        );

        $bodyPath = File::path('body.md');
        File::write($bodyPath, <<<EOT
# Hello world
EOT
        );
    }
}
