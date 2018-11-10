<?php

namespace App\Traits;

/**
 * Trait EsHighlightAttributes.
 *
 * @author overtrue <i@overtrue.me>
 */
trait EsHighlightAttributes
{
    public $searchSettings = [
        'attributesToHighlight' => [
            '*',
        ],
    ];

    public $highlights = [];

    public static function bootEsHighlightAttributes()
    {
        self::retrieved(function ($item) {
            \array_push($item->appends, 'highlights');
        });
    }

    public function getHighlightsAttribute()
    {
        return $this->highlights;
    }
}
