<?php

if (! function_exists('getRoutePermissionName')) {
    /**
     * @param string $uri
     * @param string $method
     *
     * @return string
     */
    function getRoutePermissionName($uri, $method = "*") 
    {        
        $prefix = getRoutePermissionPrefix()['prefix'];
        $separator = getRoutePermissionPrefix()['separator'];

        return $prefix.$separator.$uri.$separator.strtoupper($method);
    }
}

if (! function_exists('getRoutePermissionPrefix')) {
    /**
     * @param bool $concat
     *
     * @return array|string
     */
    function getRoutePermissionPrefix($concat = false) 
    {        
        $prefix = config('permission.rest-api.prefix');
        $separator = config('permission.rest-api.separator');

        if ($concat) {
            return $prefix.$separator;
        }

        return compact('prefix', 'separator');
    }
}

if (! function_exists('routePermissionNameToArray')) {
    /**
     * @param string $name
     *
     * @return array|bool
     */
    function routePermissionNameToArray($name) 
    {        
        $prefix = getRoutePermissionPrefix()['prefix'];
        $separator = getRoutePermissionPrefix()['separator'];

        if (strpos($name, $prefix.$separator) === 0) {
            list($prefix, $uri, $method) = explode($separator, $name);

            return compact('uri', 'method');
        }

        return false;
    }
}

if (! function_exists('getRoutePermissionNameArray')) {
    /**
     * @param array $permissions
     * @param string $method
     *
     * @return string
     */
    function getRoutePermissionNameArray($permissions) 
    {       
        return collect($permissions)
            ->map(function ($permission) {
                $uri = $permission['uri'];
                $method = $permission['method'] ?? '*';

                return getRoutePermissionName($uri, $method);
            });
    }
}