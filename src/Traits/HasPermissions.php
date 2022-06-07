<?php

namespace Timedoor\LaravelRestApiPermission\Traits;

trait HasPermissions
{
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
}
