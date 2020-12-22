<?php

namespace AwemaPL\Prestashop\Client\Api;
use AwemaPL\Prestashop\Client\PrestashopClient;

class ConnectionValidator
{
    /**
     * Validate
     *
     * @param $token
     * @return bool
     */
    public static function fail($url, $apiKey, $debug = false)
    {
        $prestashopClient = new PrestashopClient(['url' =>$url, 'api_key' =>$apiKey, 'debug' =>$debug]);
        try{
            $prestashopClient->permissions()->getPermissions();
            return '';
        } catch (PrestashopApiException $e){
            return $e->getErrorUserMessage() ?? $e->getErrorMessage();
        }
    }
}
