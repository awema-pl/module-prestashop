<?php

namespace AwemaPL\Prestashop;


use AwemaPL\Prestashop\User\Sections\Shops\Models\Shop;
use AwemaPL\Prestashop\User\Sections\Shops\Repositories\Contracts\ShopRepository;
use AwemaPL\Prestashop\User\Sections\Shops\Repositories\EloquentShopRepository;
use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Prestashop\Admin\Sections\Settings\Repositories\EloquentSettingRepository;
use AwemaPL\Prestashop\User\Sections\Shops\Policies\ShopPolicy;
use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Prestashop\Listeners\EventSubscriber;
use AwemaPL\Prestashop\Admin\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Prestashop\Admin\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Prestashop\Admin\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Prestashop\Admin\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Prestashop\Contracts\Prestashop as PrestashopContract;
use Illuminate\Support\Facades\Event;
use AwemaPL\Prestashop\Client\PrestashopClient as PrestashopClient;
use AwemaPL\Prestashop\Client\Contracts\PrestashopClient as PrestashopClientContract;

class PrestashopServiceProvider extends AwemaProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Shop::class => ShopPolicy::class,
    ];

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'prestashop');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'prestashop');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->bootMiddleware();
        app('prestashop')->includeLangJs();
        app('prestashop')->menuMerge();
        app('prestashop')->mergePermissions();
        $this->registerPolicies();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/prestashop.php', 'prestashop');
        $this->mergeConfigFrom(__DIR__ . '/../config/prestashop-menu.php', 'prestashop-menu');
        $this->app->bind(PrestashopContract::class, Prestashop::class);
        $this->app->singleton('prestashop', PrestashopContract::class);
        $this->registerRepositories();
        $this->registerServices();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'prestashop';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(ShopRepository::class, EloquentShopRepository::class);
        $this->app->bind(SettingRepository::class, EloquentSettingRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind(PrestashopClientContract::class, PrestashopClient::class);
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('prestashop', RouteMiddleware::class);
    }

    /**
     * Boot grEloquentAccountRepositoryoup middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
    }
}
