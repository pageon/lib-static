<?php

namespace Stitcher\Page;

use Stitcher\Test\StitcherTest;

class PageTest extends StitcherTest
{
    /** @test */
    public function it_can_be_created()
    {
        $page = Page::make('/home', 'index.twig');

        $this->assertInstanceOf(Page::class, $page);
    }
}
