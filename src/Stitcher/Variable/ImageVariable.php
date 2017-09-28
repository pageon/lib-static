<?php

namespace Stitcher\Variable;

use Brendt\Image\ResponsiveFactory;

class ImageVariable extends AbstractVariable
{
    private $responsiveFactory;

    public function __construct(array $value, ResponsiveFactory $responsiveFactory)
    {
        parent::__construct($value);

        $this->responsiveFactory = $responsiveFactory;
    }

    public static function make($value, ResponsiveFactory $responsiveFactory) : ImageVariable
    {
        if (is_string($value)) {
            $value = [
                'src' => $value,
            ];
        }

        return new self($value, $responsiveFactory);
    }

    public function parse() : AbstractVariable
    {
        $image = $this->responsiveFactory->create($this->value['src']);

        $this->parsed = [
            'src'    => $image->src(),
            'srcset' => $image->srcset(),
            'alt'    => $this->value['alt'] ?? null,
        ];

        return $this;
    }
}
