<?php

namespace Stitcher\Variable;

use Brendt\Image\ResponsiveFactory;

class ImageVariable extends AbstractVariable
{
    private $responsiveFactory;

    public function __construct(array $unparsed, ResponsiveFactory $responsiveFactory)
    {
        parent::__construct($unparsed);

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
        $image = $this->responsiveFactory->create($this->unparsed['src']);

        $this->parsed = [
            'src'    => $image->src(),
            'srcset' => $image->srcset(),
            'alt'    => $this->unparsed['alt'] ?? null,
        ];

        return $this;
    }
}
