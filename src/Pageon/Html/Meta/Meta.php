<?php

namespace Pageon\Html\Meta;

use Pageon\Html\Meta\Config\DefaultConfigurator;
use Pageon\Html\Meta\Item\CharsetMeta;
use Pageon\Html\Meta\Item\HttpEquivMeta;
use Pageon\Html\Meta\Item\ItemPropMeta;
use Pageon\Html\Meta\Item\LinkMeta;
use Pageon\Html\Meta\Item\NameMeta;
use Pageon\Html\Meta\Item\PropertyMeta;
use Pageon\Html\Meta\Social\GooglePlusMeta;
use Pageon\Html\Meta\Social\OpenGraphMeta;
use Pageon\Html\Meta\Social\TwitterMeta;

class Meta
{
    /** @var MetaItem[] */
    private $meta = [];
    /** @var SocialMeta[] */
    private $socialMeta = [];
    private $truncate;

    final public function __construct(string $charset = 'UTF-8') {
        $this->charset($charset);
        $this->socialMeta = [
            new GooglePlusMeta($this),
            new TwitterMeta($this),
            new OpenGraphMeta($this),
        ];
    }

    public static function create(string $charset = 'UTF-8') : Meta {
        return new self($charset);
    }

    public function render() : string {
        $html = '';

        /**
         * @var string     $type
         * @var MetaItem[] $metaItems
         */
        foreach ($this->meta as $type => $metaItems) {
            foreach ($metaItems as $metaItem) {
                $html .= $metaItem->render() . "\n";
            }
        }

        return $html;
    }

    public function charset(string $charset) : Meta {
        $item = CharsetMeta::create($charset);
        $this->meta['charset'][] = $item;

        return $this;
    }

    public function name(string $name, string $content) : Meta {
        $item = NameMeta::create($name, $content);
        $this->meta['name'][$name] = $item;

        return $this;
    }

    public function itemprop(string $name, string $content) : Meta {
        $item = ItemPropMeta::create($name, $content);
        $this->meta['itemprop'][$name] = $item;

        return $this;
    }

    public function property(string $property, string $content) : Meta {
        $item = PropertyMeta::create($property, $content);
        $this->meta['property'][$property] = $item;

        return $this;
    }

    public function httpEquiv(string $httpEquiv, string $content) : Meta {
        $item = HttpEquivMeta::create($httpEquiv, $content);
        $this->meta['httpEquiv'][$httpEquiv] = $item;

        return $this;
    }

    public function link(string $rel, string $href) : Meta {
        $item = LinkMeta::create($rel, $href);
        $this->meta['link'][$rel] = $item;

        return $this;
    }

    public function title(string $content) : Meta {
        $this->name('title', $content);

        foreach ($this->socialMeta as $socialMeta) {
            $socialMeta->title($content);
        }

        return $this;
    }

    public function description(string $content) : Meta {
        $this->name('description', $content);

        foreach ($this->socialMeta as $socialMeta) {
            $socialMeta->description($content);
        }

        return $this;
    }

    public function image(string $content) : Meta {
        $this->name('image', $content);

        foreach ($this->socialMeta as $socialMeta) {
            $socialMeta->image($content);
        }

        return $this;
    }

    public function setTruncate(int $truncate = null) : Meta {
        $this->truncate = $truncate;

        return $this;
    }
}
