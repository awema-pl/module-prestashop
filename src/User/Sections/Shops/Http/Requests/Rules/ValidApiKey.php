<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Http\Requests\Rules;
use AwemaPL\Baselinker\Client\Api\TokenValidator;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use Illuminate\Contracts\Validation\Rule;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient;

class ValidApiKey implements Rule
{
    /** @var PrestashopClient $prestashopClient */
    protected $prestashopClient;

    private $message;

    public function __construct(PrestashopClient $prestashopClient)
    {
        $this->prestashopClient = $prestashopClient;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $apiPrestashop = $this->prestashopClient->getPrestashopApi(request()->url, request()->api_key);
        $apiPrestashop->checkConnection();
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return $this->message;
    }
}
