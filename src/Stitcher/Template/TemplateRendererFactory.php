<?php

namespace Stitcher\Template;

use Stitcher\DynamicFactory;
use Stitcher\TemplateRenderer;

class TemplateRendererFactory extends DynamicFactory
{
    private $templateDirectory;

    public function __construct(string $templateDirectory)
    {
        $this->templateDirectory = $templateDirectory;

        $this->setTwigRule();
    }

    public static function make(string $templateDirectory) : TemplateRendererFactory
    {
        return new self($templateDirectory);
    }

    public function create($value) : ?TemplateRenderer
    {
        foreach ($this->getRules() as $rule) {
            $templateRenderer = $rule($value);

            if ($templateRenderer) {
                return $templateRenderer;
            }
        }

        return null;
    }

    private function setTwigRule()
    {
        $this->setRule(TwigRenderer::class, function ($value) {
            if ($value === 'twig') {
                return TwigRenderer::make($this->templateDirectory);
            }
        });
    }
}
