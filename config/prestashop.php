<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [

        // all routes is active
        'active' => true,

        // Administrator section.
        'admin' => [
            // section installations
            'installation' => [
                'active' => true,
                'prefix' => '/installation/prestashop',
                'name_prefix' => 'prestashop.admin.installation.',
                // this routes has beed except for installation module
                'expect' => [
                    'module-assets.assets',
                    'prestashop.admin.installation.index',
                    'prestashop.admin.installation.store',
                ]
            ],
            'setting' => [
                'active' => true,
                'prefix' => '/admin/prestashop/settings',
                'name_prefix' => 'prestashop.admin.setting.',
                'middleware' => [
                    'web',
                    'auth',
                    'can:manage_prestashop_settings'
                ]
            ],
        ],

        // User section
        'user' => [
            'shop' => [
                'active' => true,
                'prefix' => '/prestashop/shops',
                'name_prefix' => 'prestashop.user.shop.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages', 'manage_prestashop_settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' => 'users',
            'prestashop_shops' =>'prestashop_shops',
            'prestashop_settings' => 'prestashop_settings',
        ]
    ],

];
