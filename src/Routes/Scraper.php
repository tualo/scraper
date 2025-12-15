<?php

namespace Tualo\Office\Scraper\Routes;

use Tualo\Office\Basic\TualoApplication;
use Tualo\Office\Basic\Route;
use Tualo\Office\Scraper\ScrapCookie;


class Scraper extends \Tualo\Office\Basic\RouteWrapper
{
    public static function register()
    {

        Route::add('/scraper/scrapcookie', function () {

            $url = $_GET['url'] ?? '';
            $cookies = ScrapCookie::scrapCookie($url);
            TualoApplication::result('cookies', $cookies);
            TualoApplication::result('success', true);

            TualoApplication::contenttype('application/json');
        }, ['get'], true);
    }
}
