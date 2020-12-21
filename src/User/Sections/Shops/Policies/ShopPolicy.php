<?php
namespace AwemaPL\Prestashop\User\Sections\Shops\Policies;

use AwemaPL\Prestashop\User\Sections\Shops\Models\Shop;
use Illuminate\Foundation\Auth\User;

class ShopPolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Shop $shop
     * @return bool
     */
    public function isOwner(User $user, Shop $shop)
    {
        return $user->id === $shop->user_id;
    }


}
