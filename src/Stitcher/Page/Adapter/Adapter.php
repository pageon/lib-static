<?php

namespace Stitcher\Page\Adapter;

interface Adapter
{
    public function transform(array $pageConfiguration) : array;
}
