<?php
namespace AwemaPL\Prestashop\Client\Contracts;
use AwemaPL\Prestashop\Client\Api\PrestashopApi;

interface PrestashopClient
{
    /**
     * Get PrestaShop API
     *
     * @param $url
     * @param $apiKey
     * @param false $debug
     * @return PrestashopApi
     */
    public function getPrestashopApi($url, $apiKey, $debug = false);
}
