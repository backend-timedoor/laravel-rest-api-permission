<?php

namespace Timedoor\LaravelRestApiPermission\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Timedoor\LaravelRestApiPermission\Traits\HasRoles;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\Contracts\Permission as PermissionContract;
use Spatie\Permission\PermissionRegistrar;

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

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.role'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_permissions'),
            PermissionRegistrar::$pivotPermission,
            config('permission.column_names.model_morph_key')
        );
    }
}
