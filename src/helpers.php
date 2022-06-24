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
        $prefix = config('permission.rest-api.prefix');
        $separator = config('permission.rest-api.separator');

        return $prefix.$separator.$uri.$separator.strtoupper($method);
    }
}

if (! function_exists('routePermissionNameToArray')) {
    /**
     * @param string $name
     *
     * @return string
     */
    function routePermissionNameToArray($name) 
    {        
        $separator = config('permission.rest-api.separator');
        list($prefix, $uri, $method) = explode($separator, $name);

        return compact('prefix', 'uri', 'method');
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