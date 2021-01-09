<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Image as ImageContract;

class Image extends Client implements ImageContract
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
    public function addImage(int $idProduct, string $imagePath,  array $options = []): bool{
        return  $this->addFile(array_merge(['resource' =>'images/products', 'field' => 'image', 'id'=>$idProduct, 'path' =>$imagePath], $options));
    }
}