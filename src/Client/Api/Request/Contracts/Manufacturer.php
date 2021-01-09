<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Manufacturer
{
    /**
     * Get manufacturers
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getManufacturers(array $options): Response;

    /**
     * Get manufacturer schema blank
     *
     * @return Response
     * @throws PrestashopApiException
     */
    public function getManufacturerSchemaBlank(): Response;

    /**
     * Add manufacturer
     *
     * @param string $postXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function addManufacturer(string $postXml, array $options = []): Response;
}
