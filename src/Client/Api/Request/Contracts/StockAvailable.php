<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface StockAvailable
{
    /**
     * Get stock availables
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getStockAvailables(array $options): Response;

    /**
     * Update stock availables
     *
     * @param int $id
     * @param string $putXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function updateStockAvailables(int $id, string $putXml, array $options = []): Response;
}
