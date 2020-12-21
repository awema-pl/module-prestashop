<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Http\Requests;

use AwemaPL\Prestashop\Sections\Options\Models\Option;
use AwemaPL\Prestashop\User\Sections\Shops\Http\Requests\Rules\ValidApiKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShop extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validApiKey = app(ValidApiKey::class);
        return [
            'name' => 'required|string|max:255',
            'url' => ['required', 'string', 'max:255', $validApiKey],
            'api_key' => 'required|string|max:255',
            'local_language' =>'required|string|max:255',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => _p('prestashop::requests.user.shop.attributes.name', 'Name'),
            'url' => _p('prestashop::requests.user.shop.attributes.url', 'website address'),
            'api_key' => _p('prestashop::requests.user.shop.attributes.api_key', 'API key'),
            'local_language' => _p('prestashop::requests.user.shop.attributes.local_language', 'local language'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
