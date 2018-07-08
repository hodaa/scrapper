<?php


namespace Scrapper\Controllers;
use simple_html_dom;
use Scrapper\ScrapperProxy;
Class Controller
{
    private $proxy;

    /**
     * Controller constructor.
     * @param ScrapperProxy $proxy
     */
    function __construct(ScrapperProxy $proxy)
    {
        $this->proxy=$proxy;
    }

    /**
     * @param $view
     * @param $data
     */
    function load(string $view, array $data) :void
    {
        // Specify our Twig templates location
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Views');
        $twig = new \Twig_Environment($loader);
        echo $twig->render($view . '.html', $data);

    }

    /**
     * @return simple_html_dom
     */
    function getDomData(){

        $data=$this->proxy->get();
        $dom = new simple_html_dom();
        // Load HTML from a string
        $dom->load($data);
        return $dom;
    }
}