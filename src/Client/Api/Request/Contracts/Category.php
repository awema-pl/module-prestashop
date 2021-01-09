<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Category
{
    /**
     * Get categories
     *
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function getCategories(array $options = []): Response;

    /**
     * Get category schema blank
     *
     * @return Response
     * @throws PrestashopApiException
     */
    public function getCategorySchemaBlank(): Response;

    /**
     * Add category
     *
     * @param string $postXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function addCategory(string $postXml, array $options = []): Response;
}
