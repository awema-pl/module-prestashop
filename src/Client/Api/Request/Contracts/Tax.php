<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Tax
{
    /**
     * Get taxes
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getTaxes(array $options): Response;
}
