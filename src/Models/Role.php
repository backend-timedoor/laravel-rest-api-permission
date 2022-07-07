<?php

namespace Timedoor\RestApiPermission\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Timedoor\RestApiPermission\Traits\HasPermissions;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

class Role extends SpatieRole
{
    use HasPermissions;

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name']),
            'model',
            config('permission.table_names.model_has_roles'),
            PermissionRegistrar::$pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }
}
