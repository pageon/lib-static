<?php

namespace Stitcher\Variable;

use Pageon\Html\Image\ImageFactory;

class ImageVariable extends AbstractVariable
{
    private $imageFactory;

    public function __construct(array $unparsed, ImageFactory $imageFactory)
    {
        parent::__construct($unparsed);

        $this->imageFactory = $imageFactory;
    }

    public static function make($value, ImageFactory $imageFactory) : ImageVariable
    {
        if (is_string($value)) {
            $value = [
                'src' => $value,
            ];
        }

        return new self($value, $imageFactory);
    }

    public function parse() : AbstractVariable
    {
        $image = $this->imageFactory->create($this->unparsed['src']);

        $this->parsed = [
            'src'    => $image->src(),
            'srcset' => $image->srcset(),
            'alt'    => $this->unparsed['alt'] ?? null,
        ];

        return $this;
    }
}
