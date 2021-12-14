<?php

namespace AwemaPL\Prestashop;

use AwemaPL\Prestashop\Admin\Sections\Settings\Models\Setting;
use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use AwemaPL\Prestashop\Contracts\Prestashop as PrestashopContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Prestashop implements PrestashopContract
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;}

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveAdminInstallationRoutes() && (!$this->isMigrated())) {
                $this->adminInstallationRoutes();
            }
            if ($this->isActiveUserShopRoutes()) {
                $this->userShopRoutes();
            }
            if ($this->isActiveAdminSettingRoutes()) {
                $this->adminSettingRoutes();
            }
        }
    }

    /**
     * Admin installation routes
     */
    protected function adminInstallationRoutes()
    {
        $prefix = config('prestashop.routes.admin.installation.prefix');
        $namePrefix = config('prestashop.routes.admin.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Prestashop\Admin\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Prestashop\Admin\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * Admin setting routes
     */
    protected function adminSettingRoutes()
    {
        $prefix = config('prestashop.routes.admin.setting.prefix');
        $namePrefix = config('prestashop.routes.admin.setting.name_prefix');
        $middleware = config('prestashop.routes.admin.setting.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Prestashop\Admin\Sections\Settings\Http\Controllers\SettingController@index')
                ->name('index');
            $this->router
                ->get('/settings', '\AwemaPL\Prestashop\Admin\Sections\Settings\Http\Controllers\SettingController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Prestashop\Admin\Sections\Settings\Http\Controllers\SettingController@update')
                ->name('update');
        });
    }

    /**
     * User shop routes
     */
    protected function userShopRoutes()
    {
        $prefix = config('prestashop.routes.user.shop.prefix');
        $namePrefix = config('prestashop.routes.user.shop.name_prefix');
        $middleware = config('prestashop.routes.user.shop.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@store')
                ->name('store');
            $this->router
                ->get('/accounts', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@delete')
                ->name('delete');
            $this->router
                ->get('/select-languages', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@selectLanguage')
                ->name('select_language');
            $this->router
                ->get('/check-connection/{id?}', '\AwemaPL\Prestashop\User\Sections\Shops\Http\Controllers\ShopController@checkConnection')
                ->name('check_connection');
        });
    }

    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveAdminInstallationRoutes()
            && $canForPermission
            && (!$this->isMigrated());
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = \DB::connection()->getDoctrineSchemaManager()->listTableNames();

        $tables = array_values(config('prestashop.database.tables'));
        foreach ($tables as $table){
            if (!in_array($table, $tablesInDb)){
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('prestashop.routes.active');
    }

    /**
     * Is active admin setting routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveAdminSettingRoutes()
    {
        return config('prestashop.routes.admin.setting.active');
    }

    /**
     * Is active admin installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveAdminInstallationRoutes()
    {
        return config('prestashop.routes.admin.installation.active');
    }


    /**
     * Is active user shop routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserShopRoutes()
    {
        return config('prestashop.routes.user.shop.active');
    }

    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(Translator::class)->get('prestashop::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }

        if ($user = request()->user() ?? null){
            return $user->can(config('prestashop.installation.auto_redirect.permission'));
        }

        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()){
            $prestashopMenu = config('prestashop-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $prestashopMenu);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('prestashop-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute package migrations
     */
    public function migrate()
    {
         Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-prestashop/database/migrations']);
    }

    /**
     * Install package
     */
    public function install()
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
       if ($this->canMergePermissions()){
           $prestashopPermissions = config('prestashop.permissions');
           $tempPermissions = config('temp_permission.permissions', []);
           $permissions = array_merge_recursive($tempPermissions, $prestashopPermissions);
           config(['temp_permission.permissions' => $permissions]);
       }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('prestashop.merge_permissions');
    }
}
