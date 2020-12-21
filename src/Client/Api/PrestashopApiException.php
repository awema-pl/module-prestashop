<?php

namespace AwemaPL\Prestashop\Client\Api;
use AwemaPL\BaseJS\Exceptions\PublicException;
use Exception;

class PrestashopApiException extends PublicException
{
    const ERROR_API_PRESTASHOP = 'ERROR_API_PRESTASHOP';
    const ERROR_REQUEST_API_PRESTASHOP = 'ERROR_REQUEST_API_PRESTASHOP';
    const ERROR_XML_PROCESSING = 'ERROR_XML_PROCESSING';

}
