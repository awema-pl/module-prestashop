<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface TaxRule
{
    /**
     * Get tax rules
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getTaxRules(array $options): Response;
}
