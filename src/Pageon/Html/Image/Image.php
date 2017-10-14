<?php

namespace Pageon\Html\Image;

class Image
{
    private $src;
    private $srcset = [];

    public function __construct(string $src)
    {
        $this->src = "/{$src}";
    }

    public static function make(string $src): Image
    {
        return new self($src);
    }

    public function src(): string
    {
        return $this->src;
    }

    public function srcset(): string
    {
        return implode(', ', $this->srcset);
    }

    public function addSrcset(string $src, int $width): Image
    {
        $src = ltrim($src, '/');

        $this->srcset[] = "/{$src} {$width}w";

        return $this;
    }
}
