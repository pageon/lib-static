<?php

namespace Stitcher\Page;

class Page
{
    private $id = null;
    private $template = null;
    private $variables = [];

    public function __construct(string $id, string $template, array $variables = [])
    {
        $this->id = $id;
        $this->template = $template;
        $this->variables = $variables;
    }

    public static function make(string $id, string $template, array $variables = []): Page
    {
        return new self($id, $template, $variables);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function template(): string
    {
        return $this->template;
    }

    public function variables(): array
    {
        return $this->variables;
    }

    public function variable(string $name)
    {
        return $this->variables[$name] ?? null;
    }

}
