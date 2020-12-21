<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentShopScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchShop::class,
    ];
}
