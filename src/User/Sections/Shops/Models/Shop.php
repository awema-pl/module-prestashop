<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Prestashop\User\Sections\Shops\Models\Contracts\Shop as ShopContract;

class Shop extends Model implements ShopContract
{
    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [
        'api_key'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'name', 'url', 'api_key', 'shop_language_id','shop_language_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'shop_language_id' =>'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('prestashop.database.tables.prestashop_shops');
    }

    /**
     * Get the user that owns the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

}
