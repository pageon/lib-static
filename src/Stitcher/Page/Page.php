<?php

namespace Stitcher\Page;

use Stitcher\Configurable;
use Stitcher\Validatory;

class Page implements Configurable, Validatory
{
    private $template = null;
    private $variables = [];

    public static function create() : Page
    {
        return new self();
    }

    public function withConfig(array $config) : Page
    {
        $this->template = $config['template'] ?? null;
        $this->variables = $config['variables'] ?? [];

        return $this;
    }

    public function isValid() : bool
    {
        return $this->template !== null;
    }
}
