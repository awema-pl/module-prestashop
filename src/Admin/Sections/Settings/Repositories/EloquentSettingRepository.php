<?php

namespace AwemaPL\Prestashop\Admin\Sections\Settings\Repositories;

use AwemaPL\Prestashop\Admin\Sections\Settings\Models\Setting;
use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Prestashop\Admin\Sections\Settings\Scopes\EloquentSettingScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentSettingRepository extends BaseRepository implements SettingRepository
{
    protected $searchable = [

    ];

    public function entity()
    {
        return Setting::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentSettingScopes($request))->scope($this->entity);

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
        return Setting::create($data);
    }

    /**
     * Update setting
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
     * Get value
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key){
        $setting= Setting::where('key', $key)->first();
        if (!$setting){
            return null;
        }
        return $setting->value;
    }
}
