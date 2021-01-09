<?php

namespace AwemaPL\Prestashop\Client\Api\Request\Contracts;

use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;

interface Image
{
    /**
     * Add image
     *
     * @param int $idProduct
     * @param string $imagePath
     * @param array $options
     * @return bool
     * @throws PrestashopApiException
     */
    public function addImage(int $idProduct, string $imagePath, array $options = []): bool;
}
