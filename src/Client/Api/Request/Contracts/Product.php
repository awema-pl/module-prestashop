<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Product
{
    /**
     * Get products
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getProducts(array $options): Response;

    /**
     * Delete product
     *
     * @param int $id
     * @param array $options
     * @return bool
     * @throws PrestashopApiException
     */
    public function deleteProduct(int $id, array $options = []):bool;

    /**
     * Get product schema blank
     *
     * @return Response
     * @throws PrestashopApiException
     */
    public function getProductSchemaBlank(): Response;

    /**
     * Add product
     *
     * @param string $postXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function addProduct(string $postXml, array $options = []): Response;

    /**
     * Update product
     *
     * @param int $id
     * @param string $putXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function updateProduct(int $id, string $putXml, array $options = []): Response;
}
