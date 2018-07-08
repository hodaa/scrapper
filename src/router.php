<?php

use Scrapper\ScrapperFactory;

$router = new \Bramus\Router\Router();

$router->get('/', function () {
    $url = "https://dev98.de/";
    $scrape=ScrapperFactory::create($url);
    $scrape->index();
});
$router->get('/search', function () {
    $search = $_GET['s'];
    $url = "https://dev98.de/?s=" . $search;
    $scrape=ScrapperFactory::create($url);
    $scrape->index();
});
$router->get('/tag/(\w+)', function ($tag) {
    $url = "https://dev98.de/tag/" . $tag;
    $scrape=ScrapperFactory::create($url);
    $scrape->index();
});
$router->get('/more', function () {
    $url = $_GET['url'];
    $scrape=ScrapperFactory::create($url);
    $scrape->getDetails();
});

$router->run();
