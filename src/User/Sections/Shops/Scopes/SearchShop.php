<?php

namespace AwemaPL\Prestashop\User\Sections\Shops\Scopes;
use AwemaPL\Repository\Scopes\ScopeAbstract;

class SearchShop extends ScopeAbstract
{
    /**
     * Scope
     *
     * @param $builder
     * @param $value
     * @param $scope
     * @return mixed
     */
    public function scope($builder, $value, $scope)
    {
        if (!$value){
            return $builder;
        }

        return $builder->where('name', 'like', '%'.$value.'%');
    }
}
