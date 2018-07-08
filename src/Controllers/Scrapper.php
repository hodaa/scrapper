<?php

namespace Scrapper\Controllers;

class Scrapper extends Controller
{

    /**
     *
     */
    public function index()
    {
        $data = $this->parseData();
        $this->load('index', ['data' => $data]);
    }

    /**
     * @param $article
     * @return mixed
     */
    public function getFeaturedImage($article)
    {
        if ($article->find('div.featured-image', 0) != null) {
            $imgSrc = $article->find('div.featured-image', 0)->find('img', 0)->src;
            return $imgSrc;
        }
    }

    /**
     * @param $article
     * @return mixed
     */
    public function getDescription($article)
    {
        return $article->find('div.entry-content', 0)->innertext;
    }

    /** get summary for article in home page
     * @param $article
     * @return mixed
     */
    public function getSummary($article)
    {
        foreach ($article->find('div.entry-summary') as $element) {
            if (!empty($element)) {
                $summary = $element->find('p', 0)->innertext;
            }
        }
        return $summary;
    }

    /** get author name and create-date for each article
     * @param $item
     * @return array
     */
    public function getEntryData($item)
    {
        $data = [];

        if (empty($item->find('h5.entry-date'))) {
            $data["date"] = $item->find('time', 0)->innertext;
            $data["author"] = $item->find('span.author', 0)->children(0)->innertext;
            $data["author_url"] = $item->find('span.author', 0)->children(0)->href;
        } else {
            foreach ($item->find('h5.entry-date') as $element) {
                if (!empty($element)) {
                    $data["date"] = $element->find('time', 0)->innertext;
                    $data["author"] = $element->find('span.author', 0)->children(0)->innertext;
                    $data["author_url"] = $element->find('span.author', 0)->children(0)->href;
                }
            }
        }
        return $data;
    }

    /** get title of article
     * @param $article
     * @return mixed
     */
    public function getTitle($article)
    {
        foreach ($article->find('header') as $element) {
            if (!empty($element)) {
                $title = $element->find('h1.entry-title', 0)->innertext;
            }
        }
        return $title;
    }

    /**
     * @param $dom
     * @return array
     */
    public function getTags($dom)
    {
        $tags = [];
        foreach ($dom->find('aside#tag_cloud-2') as $element) {
            foreach ($element->find('div.tagcloud') as $tags_elements) {
                foreach ($tags_elements->children() as $key => $item) {
                    $tags[$key]['url'] = $item->href;
                    $tags[$key]['text'] = $item->innertext;
                    $tags[$key]['style'] = $item->style;
                }
            }
        }
        return $tags;
    }

    /**
     * get detalis for each article
     */
    public function getDetails()
    {
        $news = [];
        $dom = $this->getDomData();


        foreach ($dom->find('article') as $key => $item) {
            if (!empty($item)) {
                $news["image"] = $this->getFeaturedImage($item);
                $news['description'] = $this->getDescription($item);
                $news["date"] = $this->getEntryData($item)['date'];
                $news["author"] = $this->getEntryData($item)['author'];
                $news["author_url"] = $this->getEntryData($item)['author_url'];
                $news["title"] = $this->getTitle($item);
            }
        }

        $data['tags'] = $this->getTags($dom);
        $data['news'] = $news;

        $this->load('detail', ['data' => $data]);
    }

    /**
     * @return array
     */
    public function parseData()
    {
        $data = [];
        $dom = $this->getDomData();
        $news = [];
        foreach ($dom->find('article') as $key => $item) {
            if (!empty($item)) {
                $news[$key]["image"] = $this->getFeaturedImage($item);
                $news[$key]["description"] = $this->getSummary($item);


                foreach ($item->find('header') as $element) {
                    if (!empty($element)) {
                        if ($element->find('h2.entry-title', 0) !== null) {
                            $news[$key]["title_url"] = $element->find('h2.entry-title', 0)->children(0)->href;
                            $news[$key]["title"] = $element->find('h2.entry-title', 0)->children(0)->innertext;
                        } else {
                            $news[$key]["title_url"] = $element->find('h1.entry-title', 0)->children(0)->href;
                            $news[$key]["title"] = $element->find('h1.entry-title', 0)->children(0)->innertext;
                        }
                    }
                }

                $news[$key]["date"] = $this->getEntryData($item)['date'];
                $news[$key]["author"] = $this->getEntryData($item)['author'];
                $news[$key]["author_url"] = $this->getEntryData($item)['author_url'];


                foreach ($item->find('div.entry-summary') as $element) {
                    $news[$key]["read_more"] = $element->find('p.read-more', 0)->children(0)->href;
                }
            }
        }
        $tags = $this->getTags($dom);

        $data['news'] = $news;
        $data['tags'] = $tags;


        return $data;
    }
}
