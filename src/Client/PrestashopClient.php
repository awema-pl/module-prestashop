<?php

namespace AwemaPL\Prestashop\Client;

use AwemaPL\Prestashop\Client\Api\Config;
use AwemaPL\Prestashop\Client\Api\PrestashopApi;
use AwemaPL\Prestashop\Client\Api\Request\Category;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Category as CategoryContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Image as ImageContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Language as LanguageContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Manufacturer as ManufacturerContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Product as ProductContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\StockAvailable as StockAvailableContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Tax as TaxContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\TaxRule as TaxRuleContract;
use AwemaPL\Prestashop\Client\Api\Request\Image;
use AwemaPL\Prestashop\Client\Api\Request\Language;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Permission as PermissionContract;
use AwemaPL\Prestashop\Client\Api\Request\Manufacturer;
use AwemaPL\Prestashop\Client\Api\Request\Permission;
use AwemaPL\Prestashop\Client\Api\Request\Product;
use AwemaPL\Prestashop\Client\Api\Request\StockAvailable;
use AwemaPL\Prestashop\Client\Api\Request\Tax;
use AwemaPL\Prestashop\Client\Api\Request\TaxRule;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient as PrestashopClientContract;

class PrestashopClient implements PrestashopClientContract
{
    /** @var Config $config */
    private $config;

    public function __construct(array $parameters)
    {
        $this->config = new Config($parameters);
    }

    /**
     * Languages
     *
     * @return LanguageContract
     */
    public function languages(): LanguageContract
    {
        return new Language($this->config);
    }

    /**
     * Permissions
     *
     * @return PermissionContract
     */
    public function permissions(): PermissionContract
    {
        return new Permission($this->config);
    }
    /**
     * Products
     *
     * @return ProductContract
     */
    public function products(): ProductContract{
        return new Product($this->config);
    }

    /**
     * Categories
     *
     * @return CategoryContract
     */
    public function categories(): CategoryContract{
        return new Category($this->config);
    }

    /**
     * Manufacturers
     *
     * @return ManufacturerContract
     */
    public function manufacturers(): ManufacturerContract{
        return new Manufacturer($this->config);
    }

    /**
     * Taxes
     *
     * @return TaxContract
     */
    public function taxes(): TaxContract{
        return new Tax($this->config);
    }

    /**
     * Tax rules
     *
     * @return TaxRuleContract
     */
    public function taxRules(): TaxRuleContract{
        return new TaxRule($this->config);
    }

    /**
     * Stock availables
     *
     * @return StockAvailableContract
     */
    public function stockAvailables(): StockAvailableContract{
        return new StockAvailable($this->config);
    }

    /**
     * Images
     *
     * @return ImageContract
     */
    public function images(): ImageContract{
        return new Image($this->config);
    }
}
