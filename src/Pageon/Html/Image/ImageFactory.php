<?php

namespace Pageon\Html\Image;

use Intervention\Image\ImageManager;
use Intervention\Image\Image as ScaleableImage;
use Symfony\Component\Filesystem\Filesystem;

class ImageFactory
{
    private $scaler;
    private $sourceDirectory;
    private $publicDirectory;

    public function __construct(
        string $sourceDirectory,
        string $publicDirectory,
        Scaler $scaler
    ) {
        $this->sourceDirectory = rtrim($sourceDirectory, '/');
        $this->publicDirectory = rtrim($publicDirectory, '/');
        $this->scaler = $scaler;
    }

    public static function make(
        string $sourceDirectory,
        string $publicDirectory,
        Scaler $scaler
    ): ImageFactory
    {
        return new self($sourceDirectory, $publicDirectory, $scaler);
    }

    public function create($src): Image
    {
        $srcPath = ltrim($src, '/');
        $image = Image::make($srcPath);

        $fs = new Filesystem();
        $imageManager = new ImageManager([
            'driver' => 'gd',
        ]);

        $fs->copy("{$this->sourceDirectory}/{$srcPath}", "{$this->publicDirectory}/{$srcPath}");

        $scaleableImage = $imageManager->make("{$this->publicDirectory}/{$srcPath}");
        $image->addSrcset($image->src(), $scaleableImage->getWidth());

        $variations = $this->scaler->getVariations($scaleableImage);
        krsort($variations, SORT_DESC);

        foreach ($variations as $width => $height) {
            $this->createScaledImage($image, $width, $height, $scaleableImage);
        }

        return $image;
    }

    private function createScaledImage(
        Image $image,
        int $width,
        int $height,
        ScaleableImage $scaleableImage
    ) {
        $scaledFileName = $this->createScaledFileName($image, $width, $height);

        $scaleableImage
            ->resize($width, $height)
            ->save("{$this->publicDirectory}/{$scaledFileName}");

        $image->addSrcset($scaledFileName, $width);
    }

    private function createScaledFileName(Image $image, int $width, int $height): string
    {
        $srcPath = ltrim($image->src(), '/');
        $extension = pathinfo($srcPath, PATHINFO_EXTENSION);

        return str_replace(".{$extension}", "-{$width}x{$height}.{$extension}", $srcPath);
    }
}
