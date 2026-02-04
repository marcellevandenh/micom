<?php

namespace Surgems\RedirectUrls\Contracts;

interface RedirectRepository
{
    public static function all();

    public static function find($id);

    public static function findByUrl(string $uri);

    public static function make();

    public static function query();

    public function save();

    public function delete();
}
