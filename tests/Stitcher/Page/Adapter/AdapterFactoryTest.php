<?php

namespace Stitcher\Page\Adapter;

use Stitcher\Test\StitcherTest;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;

class AdapterFactoryTest extends StitcherTest
{
    /** @test */
    public function it_creates_the_correct_adapter()
    {
        $factory = AdapterFactory::make($this->createVariableParser());

        $this->assertInstanceOf(CollectionAdapter::class, $factory->create('collection', ['variable' => 'test', 'parameter' => 'id']));
    }

    private function createVariableParser() : VariableParser
    {
        return VariableParser::make(
            VariableFactory::make()
        );
    }
}
