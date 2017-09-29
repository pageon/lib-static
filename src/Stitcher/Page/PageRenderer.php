<?php

namespace Stitcher\Page;

use Stitcher\TemplateRenderer;

class PageRenderer
{
    private $renderer;

    public function __construct(TemplateRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public static function make(TemplateRenderer $renderer) : PageRenderer
    {
        return new self($renderer);
    }

    public function render(Page $page) : string
    {
        return $this->renderer->renderTemplate($page->getTemplate(), $page->getVariables());
    }
}
