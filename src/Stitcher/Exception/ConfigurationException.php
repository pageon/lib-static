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
        return new self("File with path `{$path}` could not be found.");
    }

    public static function invalidAdapterConfiguration(string $adapter, string $fields) : ConfigurationException
    {
        return new self("The {$adapter} adapter requires following configuration: {$fields}");
    }
}
