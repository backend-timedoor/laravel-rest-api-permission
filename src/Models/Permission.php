<?php

namespace Timedoor\LaravelRestApiPermission\Models;

use Timedoor\LaravelRestApiPermission\Traits\HasRoles;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasRoles;
    
    public static function createForRoute(array $attributes = [])
    {
        $attributes = self::setRouteAttributes($attributes);

        return static::create($attributes);
    }

    public static function firstOrCreateForRoute(array $attributes = [])
    {
        $attributes = self::setRouteAttributes($attributes);

        return static::firstOrcreate($attributes);
    }
    
    /**
     * Find a permission by its name for route (and optionally guardName).
     *
     * @param string $uri
     * @param string $method
     * @param string|null $guardName
     *
     * @throws \Spatie\Permission\Exceptions\PermissionDoesNotExist
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findByNameForRoute(string $uri, string $method = "*", $guardName = null): PermissionContract
    {
        $name = getRoutePermissionName($uri, $method);

        return static::findByName($name, $guardName);
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param string $uri
     * @param string $method
     * @param string|null $guardName
     *
     * @return \Spatie\Permission\Contracts\Permission
     */
    public static function findOrCreateForRoute(string $uri, string $method = "*", $guardName = null): PermissionContract
    {
        $name = getRoutePermissionName($uri, $method);

        return static::findByName($name, $guardName);
    }

    protected static function setRouteAttributes(array $attributes)
    {
        $attributes['method'] = isset($attributes['method']) ? $attributes['method'] : '*';
        $attributes['name'] = getRoutePermissionName($attributes['uri'], $attributes['method']);
        unset($attributes['uri']);
        unset($attributes['method']);

        return $attributes;
    }
}