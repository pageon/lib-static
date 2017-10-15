<?php

namespace Stitcher\Variable;

use Stitcher\File;
use Stitcher\Test\CreateStitcherObjects;
use Stitcher\Test\StitcherTest;

class VariableParserTest extends StitcherTest
{
    use CreateStitcherObjects;

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
dd($parsed);
        $this->assertTrue(isset($parsed['entry']['child']['title']));
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
