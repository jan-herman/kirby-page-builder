<?php

namespace JanHerman\PageBuilder;

use Kirby\Http\Url;

class Utils
{
    public static function option(string $key, mixed $default = null): mixed
    {
        return option('jan-herman.page-builder.' . $key, $default);
    }

    public static function normalizePath(string $path): string
    {
        return Url::path($path, true, false);
    }

    public static function pathFromOption(string $key, mixed $default = null): string
    {
        $option = self::option($key, $default);

        if (is_callable($option)) {
            return self::normalizePath($option());
        } elseif (is_string($option)) {
            return self::normalizePath($option);
        }

        return '';
    }
}
