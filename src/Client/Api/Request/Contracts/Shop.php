<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Shop
{
    /**
     * Get shops
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getShops(array $options): Response;

    /**
     * Get name by ID
     *
     * @param int $languageId
     * @return string|null
     * @throws PrestashopApiException
     */
    public function getNameById($languageId);
}
