<?php

use Scrapper\Controllers\Scrapper;
use Scrapper\ScrapperProxy;

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    $url = "https://dev98.de/";
    $scrape = new Scrapper(new ScrapperProxy($url));
    $scrape->index();
});
$router->get('/search', function () {
    $search = $_GET['s'];
    $url = "https://dev98.de/?s=" . $search;
    $scrape = new Scrapper(new ScrapperProxy($url));
    $scrape->index();
});
$router->get('/tag/(\w+)', function ($tag) {
    $url = "https://dev98.de/tag/" . $tag;
    $scrape = new Scrapper(new ScrapperProxy($url));
    $scrape->index();
});
$router->get('/more', function () {
    $url = $_GET['url'];
    $scrape = new Scrapper(new ScrapperProxy($url));
    $scrape->getDetails();
});

$router->run();
