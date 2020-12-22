<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Permission as PermissionContract;

class Permission extends Client implements PermissionContract
{

    /**
     * Get permissions
     *
     * @param array $data
     * @return Response
     * @throws BaselinkerApiException
     */
    public function getPermissions(array $options = []): Response
    {
        return new Response(
            $this->get(array_merge(['resource' =>''], $options))
        );
    }
}
