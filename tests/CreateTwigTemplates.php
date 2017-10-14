<?php

namespace Stitcher\Test;

use Stitcher\File;

trait CreateTwigTemplates
{
    protected function createIndexTemplate(): void
    {
        File::write('template/index.twig', <<<EOT
<html>
    <head>
    </head>
    <body>
    </body>
</html>
EOT
        );
    }
}
