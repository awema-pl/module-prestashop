<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Permission
{
    /**
     * Get permissions
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getPermissions(array $options): Response;
}
