<?php

namespace Scrapper;

use Scrapper\Interfaces\HttpInterface;

class ScrapperProxy implements HttpInterface
{
    protected $address;
    protected $string;

    /**
     * ScrapperProxy constructor.
     * @param $address
     * @param null $search
     */
    public function __construct($address)
    {
        $this->address = filter_var($address, FILTER_SANITIZE_URL);
    }

    /**
     * Method uses HTTP address to retrieve contents of the page
     *
     * @return  mixed
     * @throws  Exception
     */
    public function get()
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $this->address);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_HEADER, 0);
        try {
            $data = curl_exec($handle);
            $response = curl_getinfo($handle);
            if (!$response['http_code'] == 200) {
                throw new Exception;
            }
            curl_close($handle);
        } catch (Exception $e) {
            throw new Exception('Request for address: ' . $this->address . ' failed.');
        }

        $this->string = $data;
        return $this->__toString();
    }

    /**
     * Format output as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->string;
    }
}
