<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Repositories;

use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient;
use AwemaPL\Prestashop\User\Sections\Shops\Models\Shop;
use AwemaPL\Prestashop\User\Sections\Shops\Repositories\Contracts\ShopRepository;
use AwemaPL\Prestashop\User\Sections\Shops\Scopes\EloquentShopScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentShopRepository extends BaseRepository implements ShopRepository
{
    /** @var PrestashopClient $prestashopClient */
    protected $prestashopClient;

    public function __construct(PrestashopClient $prestashopClient)
    {
        parent::__construct();
        $this->prestashopClient = $prestashopClient;
    }

    protected $searchable = [

    ];

    public function entity()
    {
        return Shop::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentShopScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        return Shop::create($data);
    }

    /**
     * Update shop
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        return parent::update($data, $id, $attribute);
    }

    /**
     * Delete shop
     *
     * @param int $id
     */
    public function delete($id){
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']){
        return parent::find($id, $columns);
    }

    /**
     * Select local language
     *
     * @param string $url
     * @param string $apiKey
     * @return array
     */
    public function selectLocalLanguage($url, $apiKey)
    {
        $apiPrestashop = $this->prestashopClient->getPrestashopApi($url, $apiKey);
        $resource = $apiPrestashop->get(['resource' =>'languages', 'display' =>'[name,locale]', 'filter[active]' =>'[1]']);
        $languages = $apiPrestashop->toArray($resource);
        $localLanguage = [];
        foreach ($languages as $language){
            array_push($localLanguage, [
               'id' =>$language['locale'],
               'name' =>$language['name'],
            ]);
        }
        return $localLanguage;
    }
}
