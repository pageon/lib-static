<?php

namespace Stitcher;

interface Parseable
{
    public function parse();

    public function value();

    public function parsed();
}
