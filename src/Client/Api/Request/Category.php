<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Category as CategoryContract;

class Category extends Client implements CategoryContract
{
    /**
     * Get categories
     *
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function getCategories(array $options = []): Response
    {
        return new Response(
            $this->get(array_merge(['resource' =>'categories'], $options))
        );
    }

    /**
     * Get category schema blank
     *
     * @return Response
     * @throws PrestashopApiException
     */
    public function getCategorySchemaBlank(): Response{
        return new Response(
            $this->getSchema('categories')
        );
    }

    /**
     * Add category
     *
     * @param string $postXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function addCategory(string $postXml, array $options = []): Response
    {
        return new Response(
            $this->add(array_merge(['resource' =>'categories', 'postXml' =>$postXml], $options))
        );
    }
}
