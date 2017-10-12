<?php

namespace Pageon\Html\Meta\Item;

use PHPUnit\Framework\TestCase;

class CharsetMetaTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_created() {
        $meta = CharsetMeta::create('UTF-16');

        $this->assertNotNull($meta);
    }

    /**
     * @test
     */
    public function it_can_be_rendered() {
        $meta = CharsetMeta::create('UTF-16');
        $tag = $meta->render();

        $this->assertContains('<meta charset="UTF-16">', $tag);
    }
}
