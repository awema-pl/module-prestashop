<?php
namespace AwemaPL\Prestashop\Client\Contracts;
use AwemaPL\Prestashop\Client\Api\PrestashopApi;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Language as LanguageContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Permission as PermissionContract;

interface PrestashopClient
{
    /**
     * Languages
     *
     * @return LanguageContract
     */
    public function languages(): LanguageContract;

    /**
     * Permissions
     *
     * @return PermissionContract
     */
    public function permissions(): PermissionContract;
}
