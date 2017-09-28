<?php

namespace Stitcher\Variable;

use Stitcher\File;
use Stitcher\Test\StitcherTest;
use Symfony\Component\Yaml\Yaml;

class YamlVariableTest extends StitcherTest
{
    /** @test */
    public function it_can_be_parsed() {
        $path = File::path('/YamlVariableTest_test.yaml');
        File::put($path, $this->getYaml());

        $variable = YamlVariable::create($path, new Yaml())->parse();

        $this->assertTrue(is_array($variable->parsed()));
        $this->assertTrue(isset($variable->parsed()['root']['entry']));
    }

    private function getYaml() : string
    {
        return <<<EOT
root:
    entry:
        - a
        - b
        - c
EOT;
    }
}
