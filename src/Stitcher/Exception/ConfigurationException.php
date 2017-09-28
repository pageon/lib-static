<?php

namespace Stitcher\Exception;

class ConfigurationException extends \Exception
{
    public static function pageIdAndTemplateRequired()
    {
        return new self('To create a page, both the `id` and `template` keys are required.');
    }

    public static function fileNotFound(string $path) : ConfigurationException
    {
        throw new self("File with path `{$path}` could not be found.");
    }
}
