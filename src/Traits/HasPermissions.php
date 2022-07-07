<?php

namespace Timedoor\RestApiPermission\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasPermissions as SpatieHasPermissions;

trait HasPermissions
{
    use SpatieHasPermissions;
    
    /**
     * Grant the given route permission to a role.
     *
     * @param string $uri
     * @param string $method
     *
     * @return $this
     */
    public function givePermissionForRoute($uri, $method = "*")
    {
        $permission = getRoutePermissionName($uri, $method);

        return $this->givePermissionTo($permission);
    }

    /**
     * Revoke the given route permission.
     *
     * @param string $uri
     * @param string $method
     * 
     * @return $this
     */
    public function revokePermissionForRoute($uri, $method = "*")
    {
        $permission = getRoutePermissionName($uri, $method);

        return $this->revokePermissionTo($permission);
    }
    
    /**
     * Remove all current route permissions and set the given ones.
     *
     * @param array $permissions
     *
     * @return $this
     */
    public function syncForRoute(...$permissions)
    {
        $this->permissions()->detach();
        $permissions = getRoutePermissionNameArray($permissions);

        return $this->givePermissionTo($permissions);
    }
    
    /**
     * Determine if the model may perform the given permission.
     *
     * @param string $uri
     * @param string $method
     * @param string|null $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionForRoute($uri, $method = "*", $guardName = null): bool
    {
        $permission = getRoutePermissionName($uri, $method);

        return $this->hasPermissionTo($permission, $guard);
    }

    /**
     * A model may have multiple direct permissions.
     */
    public function permissions(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            PermissionRegistrar::$pivotPermission
        );

        if (! PermissionRegistrar::$teams) {
            return $relation;
        }

        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId());
    }
}
