<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[
            [
                'name' => 'Prestashop',
                'link' => '/prestashop/shops',
                'icon' => 'speed',
                'key' => 'prestashop::menus.prestashop',
                'children_top' => [
                    [
                        'name' => 'Shops',
                        'link' => '/prestashop/shops',
                        'key' => 'prestashop::menus.shops',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Shops',
                        'link' => '/prestashop/shops',
                        'key' => 'prestashop::menus.shops',
                    ],
                ],
            ]
        ],
        'adminSidebar' =>[
            [
                'name' => 'Settings',
                'link' => '/admin/prestashop/settings',
                'icon' => 'speed',
                'permissions' => 'manage_prestashop_settings',
                'key' => 'prestashop::menus.prestashop',
                'children_top' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/prestashop/settings',
                        'key' => 'prestashop::menus.settings',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/prestashop/settings',
                        'key' => 'prestashop::menus.settings',
                    ],
                ],
            ]
        ]
    ]
];
