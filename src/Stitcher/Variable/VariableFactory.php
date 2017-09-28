<?php

namespace Stitcher\Variable;

use Brendt\Image\ResponsiveFactory;
use Parsedown;
use Symfony\Component\Yaml\Yaml;

class VariableFactory
{
    private $rules = [];
    private $yamlParser = null;
    private $markdownParser = null;
    private $imageParser = null;

    public function __construct()
    {
        $this->setRule(JsonVariable::class, function (string $value) {
            if (is_string($value) && pathinfo($value, PATHINFO_EXTENSION) === 'json') {
                return JsonVariable::create($value);
            }

            return null;
        });

        $this->setRule(YamlVariable::class, function (string $value) {
            if ($this->yamlParser && (
                    pathinfo($value, PATHINFO_EXTENSION) === 'yaml'
                    || pathinfo($value, PATHINFO_EXTENSION) === 'yml'
                )
            ) {
                return YamlVariable::create($value, $this->yamlParser);
            }

            return null;
        });

        $this->setRule(MarkdownVariable::class, function (string $value) {
            if ($this->markdownParser && pathinfo($value, PATHINFO_EXTENSION) === 'md') {
                return MarkdownVariable::create($value, $this->markdownParser);
            }

            return null;
        });

        $this->setRule(ImageVariable::class, function ($value) {
            if (!$this->imageParser) {
                return null;
            }

            if (is_array($value)) {
                $value = $value['src'] ?? null;
            }

            $extension = pathinfo($value, PATHINFO_EXTENSION);

            if ($extension === 'jpeg'
                || $extension === 'jpg'
                || $extension === 'png'
                || $extension === 'gif'
            ) {
                return ImageVariable::create($value, $this->imageParser);
            }

            return null;
        });
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

    public function setRule(string $class, callable $callback) : VariableFactory
    {
        $this->rules[$class] = $callback;

        return $this;
    }

    public function removeRule(string $class) : VariableFactory
    {
        if (isset($this->rules[$class])) {
            unset($this->rules[$class]);
        }

        return $this;
    }

    public function create($value) : ?AbstractVariable
    {
        foreach ($this->rules as $rule) {
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
}
