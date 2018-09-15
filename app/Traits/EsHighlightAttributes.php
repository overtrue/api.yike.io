<?php


namespace App\Traits;


/**
 * Trait EsHighlightAttributes
 *
 * @author overtrue <i@overtrue.me>
 */
trait EsHighlightAttributes
{
    public $searchSettings = [
        'attributesToHighlight' => [
            '*'
        ]
    ];

    public $highlights = [];

    public function bootEsHighlightAttributes()
    {
        \array_push($this->appends, 'highlights');
    }

    public function getHighlightsAttribute()
    {
        return $this->highlights;
    }
}