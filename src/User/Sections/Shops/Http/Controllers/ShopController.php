<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers;

use AwemaPL\Prestashop\Client\Api\ConnectionValidator;
use AwemaPL\Prestashop\Client\Api\PrestashopApiException;
use AwemaPL\Prestashop\Client\PrestashopClient;
use AwemaPL\Prestashop\User\Sections\Shops\Models\Shop;
use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Prestashop\User\Sections\Shops\Http\Requests\StoreShop;
use AwemaPL\Prestashop\User\Sections\Shops\Http\Requests\UpdateShop;
use AwemaPL\Prestashop\User\Sections\Shops\Repositories\Contracts\ShopRepository;
use AwemaPL\Prestashop\User\Sections\Shops\Resources\EloquentShop;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShopController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var ShopRepository $shops */
    protected $shops;

    /** @var SettingRepository */
    protected $settings;

    public function __construct(ShopRepository $shops, SettingRepository $settings)
    {
        $this->shops = $shops;
        $this->settings = $settings;
    }

    /**
     * Display shops
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('prestashop::user.sections.shops.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentShop::collection(
            $this->shops->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create shop
     *
     * @param StoreShop $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreShop $request)
    {
          $this->shops->create($request->all());
        return notify(_p('prestashop::notifies.user.shop.success_connected_shop', 'Success connected shop.'));
    }

    /**
     * Update shop
     *
     * @param UpdateShop $request
     * @param $id
     * @return array
     */
    public function update(UpdateShop $request, $id)
    {
        $this->authorize('isOwner', Shop::find($id));
        $this->shops->update($request->all(), $id);
        return notify(_p('prestashop::notifies.user.shop.success_updated_shop', 'Success updated shop.'));
    }

    /**
     * Delete shop
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Shop::find($id));
        $this->shops->delete($id);
        return notify(_p('prestashop::notifies.user.shop.success_deleted_shop', 'Success deleted shop.'));
    }

    /**
     * Select language
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectLanguage(Request $request)
    {
        if (!filter_var($request->url, FILTER_VALIDATE_URL)){
            return $this->ajaxNotifyError(_p('prestashop::notifies.user.shop.please_enter_valid_website_address', 'Please enter a valid website address.'), 400);
        } else if (empty($request->api_key)){
            return $this->ajaxNotifyError(_p('prestashop::notifies.user.shop.please_enter_api_key', 'Please enter some API key.'), 400);
        }
        $error = ConnectionValidator::fail($request->url, $request->api_key);
        if (!empty($error)){
            return $this->ajaxNotifyError($error, 422);
        }
        return $this->ajax($this->shops->selectLanguage($request->url, $request->api_key));
    }

    /**
     * Check connection shop
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function checkConnection($id)
    {
        $this->authorize('isOwner', Shop::find($id));
        $shop = $this->shops->find($id);
        $error = ConnectionValidator::fail($shop->url, $shop->api_key);
        if (!empty($error)){
            return $this->ajaxNotifyError($error, 422);
        }
        return notify(_p('prestashop::notifies.user.shop.success_connected_shop', 'Success connected shop.'));
    }


}
