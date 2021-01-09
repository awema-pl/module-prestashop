<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Tax as TaxContract;

class Tax extends Client implements TaxContract
{

    /**
     * Get taxes
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getTaxes(array $options): Response
    {
        return new Response(
            $this->get(array_merge(['resource' =>'taxes'], $options))
        );
    }
}
