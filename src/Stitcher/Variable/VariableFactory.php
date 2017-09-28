<?php

namespace Stitcher\Variable;

use Brendt\Image\ResponsiveFactory;
use Parsedown;
use Stitcher\DynamicFactory;
use Symfony\Component\Yaml\Yaml;

class VariableFactory extends DynamicFactory
{
    private $yamlParser = null;
    private $markdownParser = null;
    private $imageParser = null;

    public function __construct()
    {
        $this->setJsonRule();
        $this->setYamlRule();
        $this->setMarkdownRule();
        $this->setImageRule();
    }

    public function setYamlParser(Yaml $yamlParser) : VariableFactory
    {
        $this->yamlParser = $yamlParser;

        return $this;
    }

    public function setMarkdownParser(Parsedown $markdownParser) : VariableFactory
    {
        $this->markdownParser = $markdownParser;

        return $this;
    }

    public function setImageParser(ResponsiveFactory $imageParser) : VariableFactory
    {
        $this->imageParser = $imageParser;

        return $this;
    }

    public function create($value) : ?AbstractVariable
    {
        foreach ($this->getRules() as $rule) {
            try {
                $variable = $rule($value);
            } catch (\TypeError $e) {
                continue;
            }

            if ($variable instanceof AbstractVariable) {
                return $variable;
            }
        }

        return null;
    }

    private function setJsonRule() : DynamicFactory
    {
        return $this->setRule(JsonVariable::class, function (string $value) {
            if (is_string($value) && pathinfo($value, PATHINFO_EXTENSION) === 'json') {
                return JsonVariable::create($value);
            }

            return null;
        });
    }

    private function setYamlRule()
    {
        $this->setRule(YamlVariable::class, function (string $value) {
            if (!$this->yamlParser) {
                return null;
            }

            $extension = pathinfo($value, PATHINFO_EXTENSION);

            if (in_array($extension, ['yaml', 'yml'])) {
                return YamlVariable::create($value, $this->yamlParser);
            }

            return null;
        });
    }

    private function setMarkdownRule()
    {
        $this->setRule(MarkdownVariable::class, function (string $value) {
            if ($this->markdownParser && pathinfo($value, PATHINFO_EXTENSION) === 'md') {
                return MarkdownVariable::create($value, $this->markdownParser);
            }

            return null;
        });
    }

    private function setImageRule()
    {
        $this->setRule(ImageVariable::class, function ($value) {
            if (!$this->imageParser) {
                return null;
            }

            if (is_array($value)) {
                $value = $value['src'] ?? null;
            }

            $extension = pathinfo($value, PATHINFO_EXTENSION);

            if (in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {
                return ImageVariable::create($value, $this->imageParser);
            }

            return null;
        });
    }
}
