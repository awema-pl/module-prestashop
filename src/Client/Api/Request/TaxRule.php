<?php

namespace AwemaPL\Prestashop\Client\Api\Request;

use AwemaPL\Prestashop\Client\Api\Client;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\Api\Response\Response;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\TaxRule as TaxRuleContract;

class TaxRule extends Client implements TaxRuleContract
{

    /**
     * Get tax rules
     *
     * @param array $data
     * @return Response
     * @throws PrestashopApiException
     */
    public function getTaxRules(array $options): Response
    {
        return new Response(
            $this->get(array_merge(['resource' =>'tax_rules'], $options))
        );
    }
}
