<?php

namespace Scrapper;

use Scrapper\Controllers\Scrapper;

class ScrapperFactory
{
    private static $instance = null;

    public static function create($url)
    {
        if (self::$instance === null) {
            self::$instance = new Scrapper(new ScrapperProxy($url));
        }
        return self::$instance;
    }
}
