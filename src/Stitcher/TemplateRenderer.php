<?php

namespace Stitcher;

interface TemplateRenderer
{
    public function renderTemplate(string $template, array $variables);
}
