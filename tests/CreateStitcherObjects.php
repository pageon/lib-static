<?php

namespace Stitcher\Test;

use Pageon\Html\Image\FixedWidthScaler;
use Pageon\Html\Image\ImageFactory;
use Parsedown;
use Stitcher\File;
use Stitcher\Page\Adapter\AdapterFactory;
use Stitcher\Page\PageFactory;
use Stitcher\Page\PageParser;
use Stitcher\Page\PageRenderer;
use Stitcher\Renderer\TwigRenderer;
use Stitcher\Variable\VariableFactory;
use Stitcher\Variable\VariableParser;
use Symfony\Component\Yaml\Yaml;

trait CreateStitcherObjects
{
    protected function createPageRenderer() : PageRenderer
    {
        return PageRenderer::make(
            TwigRenderer::make(File::path('/template'))
        );
    }

    protected function createPageParser(VariableParser $variableParser = null) : PageParser
    {
        $variableParser = $variableParser ?? $this->createVariableParser();

        return PageParser::make(
            PageFactory::make($variableParser),
            AdapterFactory::make($variableParser)
        );
    }

    protected function createVariableParser() : VariableParser
    {
        return VariableParser::make(
            VariableFactory::make()
                ->setMarkdownParser(new Parsedown())
                ->setYamlParser(new Yaml())
        );
    }

    protected function createPageFactory(VariableParser $variableParser) : PageFactory
    {
        return PageFactory::make($variableParser);
    }

    protected function createAdapterFactory(VariableParser $variableParser) : AdapterFactory
    {
        return AdapterFactory::make($variableParser);
    }

    protected function createImageFactory(): ImageFactory
    {
        $publicPath = File::path('public');

        return ImageFactory::make(__DIR__ . '/', $publicPath, FixedWidthScaler::make([
            300, 500,
        ]));
    }

    protected function createVariableFactory() : VariableFactory
    {
        $factory = VariableFactory::make()
            ->setMarkdownParser(new Parsedown())
            ->setYamlParser(new Yaml())
            ->setImageParser($this->createImageFactory());

        return $factory;
    }
}