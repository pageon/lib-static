<?php

namespace Pageon\Html\Image;

use Intervention\Image\Image;

interface Scaler
{
    public function getVariations(Image $scaleableImage) : array;
}
