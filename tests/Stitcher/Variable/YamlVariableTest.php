<?php

namespace Stitcher\Variable;

use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Symfony\Component\Yaml\Yaml;

class YamlVariableTest extends StitcherTest
{
    /** @test */
    public function it_can_be_parsed()
    {
        $path = File::path('/YamlVariableTest_test.yaml');
        File::put($path, <<<EOT
root:
    entry:
        - a
        - b
        - c
EOT
        );

        $variable = YamlVariable::create($path, new Yaml())->parse();

        $this->assertTrue(is_array($variable->parsed()));
        $this->assertTrue(isset($variable->parsed()['root']['entry']));
    }

//    /** @test */
//    public function it_can_be_parsed_recursively()
//    {
//        $parentPath = File::path('/YamlVariableTest_test_recursive_parent.yaml');
//        File::put($parentPath, <<<EOT
//entry:
//    title: Test
//    child: YamlVariableTest_test_recursive_child.yaml
//    body: body.md
//EOT
//        );
//
//        $childPath = File::path('/YamlVariableTest_test_recursive_child.yaml');
//        File::put($childPath, <<<EOT
//title: Child
//EOT
//        );
//
//        $bodyPath = File::path('/body.md');
//        File::put($bodyPath, <<<EOT
//# Hello world
//EOT
//        );
//
//        $variable = YamlVariable::create($parentPath, new Yaml())->parse();
//
//        $parsed = $variable->parsed();
//        $this->assertTrue(isset($parsed['child']['title']));
//    }
}
