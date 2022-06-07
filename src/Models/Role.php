<?php

namespace Timedoor\LaravelRestApiPermission\Models;

use Timedoor\LaravelRestApiPermission\Traits\HasPermissions;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasPermissions;
}