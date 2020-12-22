<?php

namespace AwemaPL\Prestashop\Client;

use AwemaPL\Prestashop\Client\Api\Config;
use AwemaPL\Prestashop\Client\Api\PrestashopApi;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Language as LanguageContract;
use AwemaPL\Prestashop\Client\Api\Request\Language;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Permission as PermissionContract;
use AwemaPL\Prestashop\Client\Api\Request\Permission;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient as PrestashopClientContract;

class PrestashopClient implements PrestashopClientContract
{
    /** @var Config $config */
    private $config;

    public function __construct(array $parameters)
    {
        $this->config = new Config($parameters);
    }

    /**
     * Languages
     *
     * @return LanguageContract
     */
    public function languages(): LanguageContract
    {
        return new Language($this->config);
    }

    /**
     * Permissions
     *
     * @return PermissionContract
     */
    public function permissions(): PermissionContract
    {
        return new Permission($this->config);
    }
}
