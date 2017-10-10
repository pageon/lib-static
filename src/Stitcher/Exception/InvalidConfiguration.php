<?php

namespace Stitcher\Exception;

class InvalidConfiguration extends \Exception
{
    public static function pageIdAndTemplateRequired()
    {
        return new self('To create a page, both the `id` and `template` keys are required.');
    }

    public static function fileNotFound(string $path) : InvalidConfiguration
    {
        return new self("File with path `{$path}` could not be found.");
    }

    public static function invalidAdapterConfiguration(string $adapter, string $fields) : InvalidConfiguration
    {
        return new self("The {$adapter} adapter requires following configuration: {$fields}");
    }
}
