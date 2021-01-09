<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Manufacturer as ManufacturerContract;

class Manufacturer extends Client implements ManufacturerContract
{

    /**
     * Get manufacturers
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getManufacturers(array $options): Response
    {
        return new Response(
            $this->get(array_merge(['resource' =>'manufacturers'], $options))
        );
    }

    /**
     * Get manufacturer schema blank
     *
     * @return Response
     * @throws PrestashopApiException
     */
    public function getManufacturerSchemaBlank(): Response{
        return new Response(
            $this->getSchema('manufacturers')
        );
    }

    /**
     * Add manufacturer
     *
     * @param string $postXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function addManufacturer(string $postXml, array $options = []): Response{
        return new Response(
            $this->add(array_merge(['resource' =>'manufacturers', 'postXml' =>$postXml], $options))
        );
    }
}
