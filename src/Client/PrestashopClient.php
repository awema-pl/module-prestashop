<?php

namespace AwemaPL\Prestashop\Client;

use AwemaPL\Prestashop\Client\Api\PrestashopApi;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient as PrestashopClientContract;

class PrestashopClient implements PrestashopClientContract
{
    /**
     * Get PrestaShop API
     *
     * @param $url
     * @param $apiKey
     * @param false $debug
     * @return PrestashopApi
     */
    public function getPrestashopApi($url, $apiKey, $debug = false)
    {
        return new PrestashopApi($url, $apiKey, $debug);
    }
}
