<?php

namespace Timedoor\LaravelRestApiPermission\Middlewares;

use Closure;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RestApiPermissionMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $authGuard = app('auth')->guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        
        $uri = $request->route()->uri;
        $method = $request->method();
        $permissionAllMethod = getRoutePermissionName($uri);
        $permission = getRoutePermissionName($uri, $method);
        
        if ($authGuard->user()->can($permissionAllMethod) || $authGuard->user()->can($permission)) {
            return $next($request);
        }

        $permissions[] = "{$method} {$uri}";

        throw UnauthorizedException::forPermissions($permissions);
    }
}