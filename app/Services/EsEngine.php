<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Scout\Builder;
use ScoutEngines\Elasticsearch\ElasticsearchEngine;

/**
 * Class EsEngine.
 *
 * @author overtrue <i@overtrue.me>
 */
class EsEngine extends ElasticsearchEngine
{
    public function search(Builder $builder)
    {
        $result = $this->performSearch($builder, array_filter([
            'numericFilters' => $this->filters($builder),
            'size' => $builder->limit,
        ]));

        return $result;
    }

    /**
     * Perform the given search on the engine.
     *
     * @param Builder $builder
     * @param array   $options
     *
     * @return mixed
     */
    protected function performSearch(Builder $builder, array $options = [])
    {
        $params = [
            'index' => $this->index,
            'type' => $builder->model->searchableAs(),
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query' => $builder->query,
                        'fuzziness' => 'AUTO',
                        'fields' => ['title^3', 'content'],
                    ],
                ],
            ],
        ];
        /*
         * 这里使用了 highlight 的配置
         */
        if ($builder->model->searchSettings
            && isset($builder->model->searchSettings['attributesToHighlight'])
        ) {
            $attributes = $builder->model->searchSettings['attributesToHighlight'];

            foreach ($attributes as $attribute) {
                $params['body']['highlight']['fields'][$attribute] = new \stdClass();
            }
        }

        if (isset($options['from'])) {
            $params['body']['from'] = $options['from'];
        }

        if (isset($options['size'])) {
            $params['body']['size'] = $options['size'];
        }

        if (isset($options['numericFilters']) && count($options['numericFilters'])) {
            $params['body']['query']['multi_match'] = array_merge($params['body']['query']['multi_match'],
                $options['numericFilters']);
        }

        return $this->elastic->search($params);
    }

    /**
     * Map the given results to instances of the given model.
     *
     * @param mixed                               $results
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return Collection
     */
    public function map($results, $model)
    {
        if ($results['hits']['total'] === 0) {
            return Collection::make();
        }

        $keys = collect($results['hits']['hits'])
            ->pluck('_id')->values()->all();

        $models = $model->whereIn(
            $model->getKeyName(), $keys
        )->get()->keyBy($model->getKeyName());

        return collect($results['hits']['hits'])->map(function ($hit) use ($model, $models) {
            $one = $models[$hit['_id']];
            /*
             * 这里返回的数据，如果有 highlight，就把对应的  highlight 设置到对象上面
             */
            if (isset($hit['highlight'])) {
                $one->highlights = $hit['highlight'];
            }

            return $one;
        });
    }
}
