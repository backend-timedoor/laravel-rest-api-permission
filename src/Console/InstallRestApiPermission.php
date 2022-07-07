<?php

namespace Timedoor\RestApiPermission\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallRestApiPermission extends Command
{
    protected $signature = 'restapipermission:install';

    protected $description = 'Install the Laravel Rest Api Permission';

    public function handle()
    {
        $this->info('Installing spatie/laravel-permission...');

        $this->publishSpatiePackage();

        $this->info('Installing timedoor/laravel-rest-api-permission...');

        $this->publishRestApiPermission();

        $this->info('Installed Laravel Rest Api Permission');
    }

    private function publishSpatiePackage()
    {
        $params = [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
        ];

       $this->call('vendor:publish', $params);
    }

    private function publishRestApiPermission()
    {
        $params = [
            '--provider' => "Timedoor\RestApiPermission\RestApiPermissionServiceProvider",
            '--force' => "true",
        ];

       $this->call('vendor:publish', $params);
    }
}