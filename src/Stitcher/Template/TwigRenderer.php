<?php

namespace Stitcher\Template;

use Stitcher\TemplateRenderer;
use Symfony\Component\Filesystem\Filesystem;
use Twig_Environment;

class TwigRenderer extends Twig_Environment implements TemplateRenderer
{
    public function __construct(string $templateDirectory)
    {
        $fs = new Filesystem();
        if (!$fs->exists($templateDirectory)) {
            $fs->mkdir($templateDirectory);
        }

        $loader = new \Twig_Loader_Filesystem($templateDirectory);

        parent::__construct($loader);
    }

    public static function make(string $templateDirectory) : TwigRenderer
    {
        return new self($templateDirectory);
    }

    public function renderTemplate(string $path, array $variables) : string
    {
        return $this->render($path, $variables);
    }
}
