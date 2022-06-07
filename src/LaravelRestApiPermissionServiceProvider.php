<?php

namespace Timedoor\LaravelRestApiPermission;

use Timedoor\LaravelRestApiPermission\Console\InstallLaravelRestApiPermission;
use Timedoor\LaravelRestApiPermission\Middlewares\RestApiPermissionMiddleware;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class LaravelRestApiPermissionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->offerPublishing();

        $this->registerCommands();

        $this->registerMiddlewares();
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/permission.php',
            'permission'
        );
    }

    protected function offerPublishing()
    {
        if (! function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            return;
        }

        $this->publishes([
            __DIR__.'/../config/permission.php' => config_path('permission.php'),
        ], 'config');
    }

    protected function registerCommands()
    {
        $this->commands([
            InstallLaravelRestApiPermission::class,
        ]);
    }

    protected function registerMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware(config('permission.rest-api.middleware'), RestApiPermissionMiddleware::class);
    }
}
