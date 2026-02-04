<?php

namespace Surgems\RedirectUrls\Stache\Repositories;

use Statamic\Data\ExistsAsFile;
use Statamic\Data\TracksQueriedColumns;
use Statamic\Data\TracksQueriedRelations;
use Statamic\Facades\Stache;
use Statamic\Support\Traits\FluentlyGetsAndSets;
use Surgems\RedirectUrls\Contracts\RedirectRepository as RepositoryContract;
use Surgems\RedirectUrls\Stache\Query\RedirectQueryBuilder;

class RedirectRepository implements RepositoryContract
{
    use ExistsAsFile;
    use FluentlyGetsAndSets;
    use TracksQueriedColumns;
    use TracksQueriedRelations;

    /** @var string|int */
    protected $id;

    /** @var bool */
    protected $active = true;

    /** @var string */
    protected $from;

    /** @var string */
    protected $to;

    /** @var string */
    protected $type = '301';

    public static function all()
    {
        return self::query()
            ->where('active', true)
            ->get();
    }

    public static function find($id): ?self
    {
        return self::query()
            ->where('active', true)
            ->where('id', $id)
            ->first();
    }

    public static function findByUrl(string $url)
    {
        return self::query()
            ->where('active', true)
            ->where('from', $url)
            ->first();
    }

    public static function make()
    {
        return new self();
    }

    public static function query()
    {
        return new RedirectQueryBuilder(Stache::store('redirect-urls'));
    }

    public function path()
    {
        return trim(Stache::store('redirect-urls')->directory().'/'.$this->id().'.yaml');
    }

    public function save()
    {
        if (! $this->id()) {
            $this->id(Stache::generateId());
        }

        Stache::store('redirect-urls')->save($this);

        return $this;
    }

    public function delete()
    {
        Stache::store('redirect-urls')->delete($this);

        return $this;
    }

    public function id($id = null)
    {
        return $this->fluentlyGetOrSet('id')->args(func_get_args());
    }

    public function active($active = null)
    {
        return $this->fluentlyGetOrSet('active')->args(func_get_args());
    }

    public function from($from = null)
    {
        return $this->fluentlyGetOrSet('from')->args(func_get_args());
    }

    public function to($to = null)
    {
        return $this->fluentlyGetOrSet('to')->args(func_get_args());
    }

    public function type($type = null)
    {
        return $this->fluentlyGetOrSet('type')->args(func_get_args());
    }

    public function data()
    {
        return [
            'id' => $this->id(),
            'from' => $this->from(),
            'to' => $this->to(),
            'type' => $this->type(),
            'active' => $this->active(),
        ];
    }

    public static function bindings(): array
    {
        return [
            Collection::class => \Statamic\Entries\Collection::class,
        ];
    }
}
