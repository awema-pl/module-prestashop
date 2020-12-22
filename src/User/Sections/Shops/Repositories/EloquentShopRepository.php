<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Repositories;

use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Prestashop\Client\PrestashopClient;
use AwemaPL\Prestashop\User\Sections\Shops\Models\Shop;
use AwemaPL\Prestashop\User\Sections\Shops\Repositories\Contracts\ShopRepository;
use AwemaPL\Prestashop\User\Sections\Shops\Scopes\EloquentShopScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentShopRepository extends BaseRepository implements ShopRepository
{

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
        $data['shop_language_name'] =(new PrestashopClient(['url'=>$data['url'], 'api_key' =>$data['api_key']]))->languages()->getNameLanguageById($data['shop_language_id']);
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
        $data['shop_language_name'] =(new PrestashopClient(['url'=>$data['url'], 'api_key' =>$data['api_key']]))->languages()->getNameLanguageById($data['shop_language_id']);
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
     * Select language
     *
     * @param string $url
     * @param string $apiKey
     * @return array
     */
    public function selectLanguage($url, $apiKey)
    {
        $languages = (new PrestashopClient(['url'=>$url, 'api_key' =>$apiKey]))->languages()
            ->getLanguages(['display' =>'[id,name]', 'filter[active]' =>'[1]'])
        ->toArray();
        $data = [];
        foreach ($languages as $language){
            array_push($data, [
               'id' =>(int)$language['id'],
               'name' =>$language['name'],
            ]);
        }
        return $data;
    }
}
