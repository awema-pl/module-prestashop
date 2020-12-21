<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Repositories\Contracts;

use AwemaPL\Prestashop\User\Sections\Shops\Repositories\EloquentShopRepository;
use AwemaPL\Prestashop\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface ShopRepository
{
    /**
     * Create shop
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope shop
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update shop
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete shop
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);

    /**
     * Select local language
     *
     * @param string $url
     * @param string $apiKey
     * @return array
     */
    public function selectLocalLanguage($url, $apiKey);
}
