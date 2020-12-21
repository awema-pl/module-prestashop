<?php

namespace AwemaPL\Prestashop\Admin\Sections\Installations\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use AwemaPL\Prestashop\Facades\Prestashop;

class Installation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Prestashop::canInstallation()){
            $route = Route::getRoutes()->match($request);
            $name = $route->getName();
            if (!in_array($name, config('prestashop.routes.admin.installation.expect'))){
                return redirect()->route('prestashop.admin.installation.index');
            }
        }
        return $next($request);
    }
}
