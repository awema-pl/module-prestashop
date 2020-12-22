<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Http\Requests\Rules;
use AwemaPL\Baselinker\Client\Api\TokenValidator;
use AwemaPL\Prestashop\Client\Api\ConnectionValidator;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use Illuminate\Contracts\Validation\Rule;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient;

class ValidApiKey implements Rule
{
    private $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $this->message = ConnectionValidator::fail(request()->url, request()->api_key);
        return empty($this->message);
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
