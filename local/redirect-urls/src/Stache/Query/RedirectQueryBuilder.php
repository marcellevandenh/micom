<?php

namespace Surgems\RedirectUrls\Stache\Query;

use Statamic\Data\DataCollection;
use Statamic\Stache\Query\Builder;

class RedirectQueryBuilder extends Builder
{
    protected function getFilteredKeys()
    {
        if (! empty($this->wheres)) {
            return $this->getKeysWithWheres();
        }

        return collect($this->store->paths()->keys());
    }

    protected function getKeysWithWheres()
    {
        return collect($this->wheres)->reduce(function ($ids, $where) {
            $items = app('stache')
                ->store('redirect-urls')
                ->index($where['column'])->items();

            $keys = $this->filterWhereBasic($items, $where)->keys();

            return $ids ? $ids->intersect($keys)->values() : $keys;
        });
    }

    protected function collect($items = [])
    {
        return new DataCollection($items);
    }

    protected function getOrderKeyValuesByIndex()
    {
        return collect($this->orderBys)->mapWithKeys(function ($orderBy) {
            $items = $this->store->index($orderBy->sort)->items()->all();

            return [$orderBy->sort => $items];
        });
    }

    protected function getWhereColumnKeyValuesByIndex($column)
    {
        return app('stache')
            ->store('redirect-urls')
            ->index($column)->items();
    }
}
