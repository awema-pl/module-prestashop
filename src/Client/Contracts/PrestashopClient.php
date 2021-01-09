<?php
namespace AwemaPL\Prestashop\Client\Contracts;
use AwemaPL\Prestashop\Client\Api\PrestashopApi;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Language as LanguageContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Permission as PermissionContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Product as ProductContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Category as CategoryContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Manufacturer as ManufacturerContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Tax as TaxContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\TaxRule as TaxRuleContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\StockAvailable as StockAvailableContract;
use AwemaPL\Prestashop\Client\Api\Request\Contracts\Image as ImageContract;

interface PrestashopClient
{
    /**
     * Languages
     *
     * @return LanguageContract
     */
    public function languages(): LanguageContract;

    /**
     * Permissions
     *
     * @return PermissionContract
     */
    public function permissions(): PermissionContract;

    /**
     * Products
     *
     * @return ProductContract
     */
    public function products(): ProductContract;

    /**
     * Categories
     *
     * @return CategoryContract
     */
    public function categories(): CategoryContract;

    /**
     * Manufacturers
     *
     * @return ManufacturerContract
     */
    public function manufacturers(): ManufacturerContract;

    /**
     * Taxes
     *
     * @return TaxContract
     */
    public function taxes(): TaxContract;

    /**
     * Tax rules
     *
     * @return TaxRuleContract
     */
    public function taxRules(): TaxRuleContract;

    /**
     * Stock availables
     *
     * @return StockAvailableContract
     */
    public function stockAvailables(): StockAvailableContract;
    
    /**
     * Images
     *
     * @return ImageContract
     */
    public function images(): ImageContract;
}
