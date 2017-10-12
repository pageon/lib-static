<?php

namespace Pageon\Html\Image;

use Intervention\Image\Image as ScaleableImage;
use Intervention\Image\ImageManager;
use Symfony\Component\Filesystem\Filesystem;

class Image
{
    private $src;
    private $srcset = [];
    private $sourceDirectory = null;
    /** @var Scaler */
    private $scaler;

    public function __construct(string $src)
    {
        $this->src = ltrim($src, '/');
    }

    public static function make(string $src): Image
    {
        return new self($src);
    }

    public function src(): string
    {
        return "/{$this->src}";
    }

    public function srcset(): string
    {
        return implode(', ', $this->srcset);
    }

    public function withScaler(Scaler $scaler): Image
    {
        $this->scaler = $scaler;

        return $this;
    }

    public function loadFrom(string $directory): Image
    {
        $this->sourceDirectory = rtrim($directory, '/');

        return $this;
    }

    public function addSrcset(string $src, int $width): Image
    {
        $this->srcset[] = "/{$src} {$width}w";

        return $this;
    }

    public function saveIn(string $publicDirectory): Image
    {
        $fs = new Filesystem();
        $imageManager = new ImageManager([
            'driver' => 'gd',
        ]);

        $fs->copy("{$this->sourceDirectory}/{$this->src}", "{$publicDirectory}/{$this->src}");

        $scaleableImage = $imageManager->make("{$publicDirectory}/{$this->src}");
        $this->addSrcset($this->src, $scaleableImage->getWidth());

        $variations = $this->scaler->getVariations($scaleableImage);
        krsort($variations, SORT_DESC);

        foreach ($variations as $width => $height) {
            $this->createScaledImage($publicDirectory, $width, $height, $scaleableImage);
        }

        return $this;
    }

    private function createScaledImage(
        string $publicDirectory,
        int $width,
        int $height,
        ScaleableImage $scaleableImage
    ) {
        $scaledFileName = $this->createScaledFileName($width, $height);

        $scaleableImage
            ->resize($width, $height)
            ->save("{$publicDirectory}/{$scaledFileName}");

        $this->addSrcset($scaledFileName, $width);
    }

    private function createScaledFileName(int $width, int $height): string
    {
        $extension = pathinfo($this->src, PATHINFO_EXTENSION);

        return str_replace(".{$extension}", "-{$width}x{$height}.{$extension}", $this->src);
    }
}
