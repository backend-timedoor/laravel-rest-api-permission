# Laravel REST API Permission

Extended from [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction) version 5, this package help developer authorize user permission based on URI and request method like REST API.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install this package.

```bash
composer require timedoor/laravel-rest-api-permission
```

Publish the package in your project with:

```bash
php artisan restapipermission:install
```

Then run the database migration.

```bash
php artisan migrate
```

## Usage

You can use provided route middleware alias from this package named `rest-api-permission` that can be configured from `config/permission.php`, for example:

```php
Route::middleware(['rest-api-permission'])->group(function () {
    //
});
```
Your user must be logged in if you use this middleware, this middleware will automatically match logged-in user permission with the current route URI.

For creating permission for REST API you can use provided permission model by this package, for example:

```php
use Timedoor\LaravelRestApiPermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::createForRoute(['uri' => 'api/user/{id}', 'POST']);
```

Make sure you include the `Timedoor\LaravelRestApiPermission` for role and permission models to use the extended features for the route.

For the `uri` value use string provided from one of the below codes for accurate reference.

```php
\Route::current()->uri(); // Return current route URI.

collect(\Route::getRoutes())->map(function ($route) { return $route->uri(); }) // Listing all registered route URI.
```

In the database, the permission name will be stored as `rest-api>>api/user/>>*` (if the second parameter isn't provided it will be stored as `*`) or `rest-api>>api/user/{id}/>>POST`, you can access it with default `spatie/laravel-permission` utility like:

```php
$user->can('rest-api>>api/user/{id}/>>POST');
```

Or using provided helper function to generate formatted permission name.

```php
$user->can(getRoutePermissionName('api/user/{id}', 'POST'));
```

For manipulating route permission here's the list of other extended methods:

```php
use Timedoor\LaravelRestApiPermission\Models\Permission;

Permission::createForRoute(['uri' => 'api/user']);

Permission::firstOrCreateForRoute(['uri' => 'api/user']);

Permission::findByNameForRoute('api/user/{id}', 'POST');

Permission::findOrCreateForRoute('api/user/{id}', 'POST');

// Scope a query to only include rest api permissions.
Permission::restApi()->get();

// Scope a query to not include rest api permissions.
Permission::withoutRestApi()->get();

// You can call below methods from role instance too.
$user->givePermissionForRoute('api/user/{id}', 'POST');

$user->revokePermissionForRoute('api/user/{id}', 'POST');

$user->syncForRoute([
    ['uri' => 'api/user'],
    ['uri' => 'api/user/{id}', 'method' => 'POST'],
]);

$user->hasPermissionForRoute('api/user/{id}', 'POST');
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
[MIT](https://choosealicense.com/licenses/mit/)