<?php

namespace Pageon\Html;

class SiteMap
{
    private $urls = [];
    private $hostname = '';

    public function __construct(string $hostname)
    {
        $this->hostname = trim((string) $hostname, '/');
    }

    public static function make(string $hostname): SiteMap
    {
        return new self($hostname);
    }

    public function addPath(string $path): SiteMap
    {
        $path = trim($path, '/');

        $this->urls[] = "{$this->hostname}/{$path}";

        return $this;
    }

    public function render(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $dateModified = date('c');

        foreach ($this->urls as $url) {
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>{$url}</loc>\n";
            $xml .= "\t\t<lastmod>{$dateModified}</lastmod>\n";
            $xml .= "\t</url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
