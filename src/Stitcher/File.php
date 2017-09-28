<?php

namespace Stitcher;

use Symfony\Component\Filesystem\Filesystem;

class File
{
    private static $fs;
    private static $base;

    public static function base(?string $base)
    {
        self::$base = rtrim($base, '/');
    }

    public static function path(string $path = null) : string
    {
        $path = str_replace(self::$base, '', $path);

        return self::$base . $path;
    }

    public static function get(string $path)
    {
        return @file_get_contents(self::path($path));
    }

    public static function put(string $path, $content = null)
    {
        if (!self::$fs) {
            self::$fs = new Filesystem();
        }

        self::$fs->dumpFile(self::path($path), $content);
    }
}