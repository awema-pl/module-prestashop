<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Language
{
    /**
     * Get languages
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getLanguages(array $options): Response;

    /**
     * Get name language by ID
     *
     * @param int $languageId
     * @return string|null
     * @throws PrestashopApiException
     */
    public function getNameLanguageById($languageId);
}
