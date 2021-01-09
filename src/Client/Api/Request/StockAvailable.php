<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\StockAvailable as StockAvailableContract;

class StockAvailable extends Client implements StockAvailableContract
{
    /**
     * Get stock availables
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getStockAvailables(array $options): Response{
        return new Response(
            $this->get(array_merge(['resource' =>'stock_availables'], $options))
        );
    }

    /**
     * Update stock availables
     *
     * @param int $id
     * @param string $putXml
     * @param array $options
     * @return Response
     * @throws PrestashopApiException
     */
    public function updateStockAvailables(int $id, string $putXml, array $options = []): Response{
        return new Response(
            $this->edit(array_merge(['resource' =>'stock_availables', 'id' => $id, 'putXml' =>$putXml], $options))
        );
    }
}
